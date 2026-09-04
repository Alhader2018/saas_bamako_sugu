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
        $items = CartService::getCart();

        if (empty($items)) {
            $this->addError('cart', 'Votre panier est vide.');
            return;
        }

        $this->validate();

        $subtotal = CartService::subtotal();
        $deliveryFee = CartService::deliveryFee();
        $total = CartService::total();

        $orderNumber = 'BKO-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));

        $order = Order::create([
            'order_number' => $orderNumber,
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
            'payment_status' => $this->paymentMethod === 'orange_money' ? 'paid' : 'pending',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'discount' => 0,
            'total' => $total,
            'status' => 'confirmed',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_image' => $item['image_url'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }

        CartService::clear();
        $this->createdOrder = $order;
        $this->orderCompleted = true;
        $this->dispatch('cart-updated');
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
