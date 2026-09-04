<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Livewire\Component;

class CheckoutForm extends Component
{
    // Coordonnées
    public string $firstName = '';
    public string $lastName = '';
    public string $phone = '+223 ';
    public string $email = '';

    // Livraison Bamako
    public string $city = 'Bamako';
    public string $neighborhood = 'ACI 2000';
    public string $address = '';
    public string $deliveryNotes = '';

    // Paiement
    public string $paymentMethod = 'orange_money'; // 'orange_money', 'cash_on_delivery'
    public string $orangeMoneyNumber = '';

    public bool $orderCompleted = false;
    public ?Order $createdOrder = null;

    protected function rules(): array
    {
        return [
            'firstName' => 'required|string|min:2|max:100',
            'lastName' => 'required|string|min:2|max:100',
            'phone' => 'required|string|min:8',
            'email' => 'nullable|email',
            'city' => 'required|string',
            'neighborhood' => 'required|string',
            'address' => 'required|string|min:4',
            'deliveryNotes' => 'nullable|string',
            'paymentMethod' => 'required|in:orange_money,cash_on_delivery',
            'orangeMoneyNumber' => 'required_if:paymentMethod,orange_money',
        ];
    }

    protected $messages = [
        'firstName.required' => 'Veuillez saisir votre prénom.',
        'lastName.required' => 'Veuillez saisir votre nom.',
        'phone.required' => 'Le numéro de téléphone malien (+223) est requis.',
        'address.required' => 'Veuillez préciser votre adresse ou un repère connu.',
        'neighborhood.required' => 'Veuillez choisir votre quartier à Bamako.',
        'orangeMoneyNumber.required_if' => 'Veuillez renseigner votre numéro Orange Money.',
    ];

    public function mount(): void
    {
        if (CartService::count() === 0 && !$this->orderCompleted) {
            // Le panier est vide
        }
    }

    public function setPaymentMethod(string $method): void
    {
        $this->paymentMethod = $method;
        if ($method === 'orange_money' && empty($this->orangeMoneyNumber) && !empty($this->phone) && $this->phone !== '+223 ') {
            $this->orangeMoneyNumber = $this->phone;
        }
    }

    public function submitOrder()
    {
        $cartItems = CartService::getCart();

        if (empty($cartItems)) {
            $this->addError('cart', 'Votre panier est vide.');
            return;
        }

        $this->validate();

        $productIds = array_keys($cartItems);

        try {
            $order = \Illuminate\Support\Facades\DB::transaction(function () use ($cartItems, $productIds) {
                // 1. Verrouillage pessimiste pour éviter les race conditions sur le stock
                $products = \App\Models\Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                $calculatedSubtotal = 0;
                $orderItemsData = [];

                foreach ($cartItems as $productId => $item) {
                    $product = $products->get($productId);

                    if (!$product) {
                        throw new \RuntimeException("L'article {$item['name']} n'est plus disponible.");
                    }

                    $quantity = (int) $item['quantity'];
                    if ($quantity <= 0) {
                        throw new \RuntimeException("Quantité invalide pour {$product->name}.");
                    }

                    // 2. Vérification stricte du stock serveur
                    if ($product->stock < $quantity) {
                        throw new \RuntimeException("Stock insuffisant pour {$product->name} (disponible : {$product->stock}).");
                    }

                    // 3. Recalcul du prix basé exclusivement sur la base de données (pas la session)
                    $unitPrice = (int) $product->price;
                    $lineTotal = $unitPrice * $quantity;
                    $calculatedSubtotal += $lineTotal;

                    // Décrémentation immédiate du stock
                    $product->decrement('stock', $quantity);

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_image' => $product->image_url,
                        'price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $lineTotal,
                    ];
                }

                // Frais de livraison recalculés serveur
                $deliveryFee = $calculatedSubtotal >= 50000 ? 0 : CartService::DELIVERY_FEE;
                $grandTotal = $calculatedSubtotal + $deliveryFee;

                $orderNumber = 'BKO-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));
                $trackingToken = \Illuminate\Support\Str::random(40);

                // 4. Création de la commande avec statut initial 'pending'
                $created = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => $orderNumber,
                    'tracking_token' => $trackingToken,
                    'customer_first_name' => $this->firstName,
                    'customer_last_name' => $this->lastName,
                    'customer_phone' => $this->phone,
                    'customer_email' => $this->email ?: null,
                    'city' => $this->city,
                    'neighborhood' => $this->neighborhood,
                    'address' => $this->address,
                    'delivery_notes' => $this->deliveryNotes ?: null,
                    'payment_method' => $this->paymentMethod,
                    'orange_money_number' => $this->paymentMethod === 'orange_money' ? $this->orangeMoneyNumber : null,
                    'payment_status' => 'pending', // Strictement pending jusqu'à confirmation externe
                    'subtotal' => $calculatedSubtotal,
                    'delivery_fee' => $deliveryFee,
                    'discount' => 0,
                    'total' => $grandTotal,
                    'status' => 'confirmed',
                ]);

                foreach ($orderItemsData as $itemData) {
                    $itemData['order_id'] = $created->id;
                    OrderItem::create($itemData);
                }

                return $created;
            });
        } catch (\Throwable $e) {
            $this->addError('cart', $e->getMessage());
            return;
        }

        // Sauvegarder le token dans la session du visiteur pour l'autoriser à voir sa commande (protection anti-IDOR)
        session()->put('accessible_order_tokens.' . $order->order_number, $order->tracking_token);

        CartService::clear();
        $this->createdOrder = $order;
        $this->dispatch('cart-updated');

        // Si Orange Money, initier le WebPayment
        if ($this->paymentMethod === 'orange_money' && config('services.orange_money.client_id') && config('services.orange_money.merchant_key')) {
            try {
                $orangeMoneyService = app(\App\Services\OrangeMoneyService::class);
                $paymentData = $orangeMoneyService->createWebPayment($order);
                if (!empty($paymentData['payment_url'])) {
                    return redirect()->away($paymentData['payment_url']);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Erreur initiation Orange Money WebPayment: ' . $e->getMessage());
            }
        }

        $this->orderCompleted = true;
    }

    public function render()
    {
        $neighborhoods = [
            'ACI 2000',
            'Badalabougou',
            'Hamdallaye ACI',
            'Hamdallaye',
            'Hippodrome',
            'Hippodrome II',
            'Quinzambougou',
            'Médina Coura',
            'Niaréla',
            'Bagadadji',
            'Bozola',
            'Sotuba',
            'Sotuba ACI',
            'Korofina Nord',
            'Korofina Sud',
            'Djelibougou',
            'Doumanzana',
            'Boulkassoumbougou',
            'Faladié',
            'Faladié SEMA',
            'Baco-Djicoroni ACI',
            'Baco-Djicoroni Golf',
            'Kalaban-Coura',
            'Kalaban-Coro',
            'Sébénikoro',
            'Djicoroni-Para',
            'Lafiabougou',
            'Lafiabougou Bougoudani',
            'Torokorobougou',
            'Daoudabougou',
            'Saballibougou',
            'Yirimadio',
            'Missabougou',
            'Attbougou (1008 Logements)',
        ];

        return view('livewire.checkout-form', [
            'items' => CartService::getCart(),
            'subtotal' => CartService::subtotal(),
            'deliveryFee' => CartService::deliveryFee(),
            'total' => CartService::total(),
            'neighborhoods' => $neighborhoods,
        ]);
    }
}
