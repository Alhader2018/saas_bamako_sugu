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

    public function rules(): array
    {
        $isPurelyDigital = CartService::isPurelyDigital();
        $hasDigital = CartService::hasDigitalItems();

        return [
            'firstName' => 'required|string|min:2|max:100',
            'lastName' => 'required|string|min:2|max:100',
            'phone' => 'required|string|min:8',
            'email' => $hasDigital ? 'required|email' : 'nullable|email',
            'city' => $isPurelyDigital ? 'nullable|string' : 'required|string',
            'neighborhood' => $isPurelyDigital ? 'nullable|string' : 'required|string',
            'address' => $isPurelyDigital ? 'nullable|string' : 'required|string|min:4',
            'deliveryNotes' => 'nullable|string',
            'paymentMethod' => $isPurelyDigital ? 'required|in:orange_money' : 'required|in:orange_money,cash_on_delivery',
            'orangeMoneyNumber' => 'required_if:paymentMethod,orange_money',
        ];
    }

    protected function messages(): array
    {
        return [
            'firstName.required' => 'Veuillez saisir votre prénom.',
            'lastName.required' => 'Veuillez saisir votre nom.',
            'phone.required' => 'Le numéro de téléphone malien (+223) est requis.',
            'email.required' => 'Votre adresse email est indispensable pour recevoir vos accès aux fichiers numériques.',
            'address.required' => 'Veuillez préciser votre adresse ou un repère connu.',
            'neighborhood.required' => 'Veuillez choisir votre quartier à Bamako.',
            'orangeMoneyNumber.required_if' => 'Veuillez renseigner votre numéro Orange Money.',
            'paymentMethod.in' => 'Le paiement à la livraison n\'est pas disponible pour les produits numériques.',
        ];
    }

    public function mount(): void
    {
        if (CartService::count() === 0 && !$this->orderCompleted) {
            // Le panier est vide
        }

        if (CartService::isPurelyDigital()) {
            $this->paymentMethod = 'orange_money';
        }
    }

    public function setPaymentMethod(string $method): void
    {
        if (CartService::isPurelyDigital() && $method === 'cash_on_delivery') {
            $this->paymentMethod = 'orange_money';
            return;
        }

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

        // Si le panier est 100% digital, s'assurer que le mode de paiement n'est pas cash
        if (CartService::isPurelyDigital()) {
            $this->paymentMethod = 'orange_money';
        }

        $this->validate();

        $productIds = array_keys($cartItems);

        try {
            $order = \Illuminate\Support\Facades\DB::transaction(function () use ($cartItems, $productIds) {
                // 1. Verrouillage pessimiste pour éviter les race conditions sur le stock
                $products = \App\Models\Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                $calculatedSubtotal = 0;
                $physicalSubtotal = 0;
                $hasPhysical = false;
                $orderItemsData = [];

                foreach ($cartItems as $productId => $item) {
                    $product = $products->get($productId);

                    if (!$product) {
                        throw new \RuntimeException("L'article {$item['name']} n'est plus disponible.");
                    }

                    $isDigital = $product->isDigital();
                    $quantity = $isDigital ? 1 : (int) $item['quantity'];
                    if ($quantity <= 0) {
                        throw new \RuntimeException("Quantité invalide pour {$product->name}.");
                    }

                    // 2. Vérification stricte du stock serveur (uniquement pour les produits physiques)
                    if (!$isDigital) {
                        $hasPhysical = true;
                        if ($product->stock < $quantity) {
                            throw new \RuntimeException("Stock insuffisant pour {$product->name} (disponible : {$product->stock}).");
                        }
                        // Décrémentation immédiate du stock
                        $product->decrement('stock', $quantity);
                    }

                    // 3. Recalcul du prix basé exclusivement sur la base de données (pas la session)
                    $unitPrice = (int) $product->price;
                    $lineTotal = $unitPrice * $quantity;
                    $calculatedSubtotal += $lineTotal;
                    if (!$isDigital) {
                        $physicalSubtotal += $lineTotal;
                    }

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'product_type' => $isDigital ? 'digital' : 'physical',
                        'product_name' => $product->name,
                        'product_image' => $product->image_url,
                        'price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $lineTotal,
                    ];
                }

                // Frais de livraison : 0 FCFA si que du digital, sinon règle physique (offert dès 50k)
                $deliveryFee = (!$hasPhysical) ? 0 : ($physicalSubtotal >= 50000 ? 0 : CartService::DELIVERY_FEE);
                $grandTotal = $calculatedSubtotal + $deliveryFee;

                $orderNumber = 'BKO-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));
                $trackingToken = \Illuminate\Support\Str::random(40);

                // 4. Création de la commande avec statut initial
                $created = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => $orderNumber,
                    'tracking_token' => $trackingToken,
                    'customer_first_name' => $this->firstName,
                    'customer_last_name' => $this->lastName,
                    'customer_phone' => $this->phone,
                    'customer_email' => $this->email ?: null,
                    'city' => !empty($this->city) ? $this->city : 'Bamako',
                    'neighborhood' => !empty($this->neighborhood) ? $this->neighborhood : 'En ligne / Téléchargement',
                    'address' => !empty($this->address) ? $this->address : 'Livraison numérique immédiate',
                    'delivery_notes' => $this->deliveryNotes ?: null,
                    'payment_method' => $this->paymentMethod,
                    'orange_money_number' => $this->paymentMethod === 'orange_money' ? $this->orangeMoneyNumber : null,
                    'payment_status' => 'pending', // Strictement pending jusqu'à confirmation externe
                    'subtotal' => $calculatedSubtotal,
                    'delivery_fee' => $deliveryFee,
                    'discount' => 0,
                    'total' => $grandTotal,
                    'status' => $this->paymentMethod === 'orange_money' ? 'pending' : 'confirmed',
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
