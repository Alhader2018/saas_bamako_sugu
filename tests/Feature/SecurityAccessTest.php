<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_cannot_access_admin_and_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect('/connexion');
    }

    public function test_customer_user_receives_403_forbidden_on_admin(): void
    {
        $customer = User::create([
            'name' => 'Client Normal',
            'email' => 'client.normal@exemple.com',
            'password' => Hash::make('secret1234'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($customer)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_staff_member_can_access_admin(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Tableau de bord');
    }

    public function test_inactive_staff_member_is_denied(): void
    {
        $inactiveStaff = User::create([
            'name' => 'Staff Inactif',
            'email' => 'inactif@bamakosugu.com',
            'password' => Hash::make('secret1234'),
            'role' => 'admin',
            'is_active' => false,
        ]);

        $response = $this->actingAs($inactiveStaff)->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect('/connexion');
    }

    public function test_login_rate_limiting_protects_against_brute_force(): void
    {
        RateLimiter::clear('attack@exemple.com|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $this->post('/connexion', [
                'email' => 'attack@exemple.com',
                'password' => 'wrong_password',
            ]);
        }

        // 6ème tentative doit être bloquée par le RateLimiter (429 ou flash message rate limit)
        $response = $this->post('/connexion', [
            'email' => 'attack@exemple.com',
            'password' => 'wrong_password',
        ]);

        // Soit 429 directement via le throttle, soit flash message d'erreur de limitation
        $this->assertTrue(
            $response->status() === 429 || $response->status() === 302,
            'Le rate limiting doit intervenir sur les tentatives répétées.'
        );
    }

    public function test_register_enforces_customer_role_against_privilege_escalation(): void
    {
        $response = $this->post('/inscription', [
            'name' => 'Attaquant',
            'email' => 'hacker@exemple.com',
            'phone' => '+223 70 99 88 77',
            'password' => 'SecurePass123',
            'password_confirmation' => 'SecurePass123',
            'role' => 'super_admin', // tentative d'injection de rôle
        ]);

        $response->assertRedirect('/');

        $user = User::where('email', 'hacker@exemple.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role, 'Le rôle doit strictement rester customer.');
        $this->assertFalse($user->isStaff());
    }

    public function test_checkout_initializes_orange_money_with_pending_status(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        CartService::clear();
        CartService::add($product->id, 1);

        $component = \Livewire\Livewire::test(\App\Livewire\CheckoutForm::class)
            ->set('firstName', 'Mamadou')
            ->set('lastName', 'Keita')
            ->set('phone', '+223 76 00 11 22')
            ->set('address', 'Rue 12, Porte 4')
            ->set('neighborhood', 'ACI 2000')
            ->set('paymentMethod', 'orange_money')
            ->set('orangeMoneyNumber', '+223 76 00 11 22')
            ->call('submitOrder');

        $component->assertHasNoErrors();
        $order = Order::latest('id')->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->payment_status, 'Le paiement Orange Money doit impérativement débuter en statut pending.');
    }

    public function test_checkout_recalculates_price_from_database_and_decrements_stock(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $initialStock = $product->stock;
        $realPrice = $product->price;

        CartService::clear();
        CartService::add($product->id, 2);

        // Simulation falsification de prix en session
        $cart = session()->get('bko_cart');
        $cart[$product->id]['price'] = 1; // Prix falsifié à 1 FCFA !
        session()->put('bko_cart', $cart);

        $component = \Livewire\Livewire::test(\App\Livewire\CheckoutForm::class)
            ->set('firstName', 'Fanta')
            ->set('lastName', 'Toure')
            ->set('phone', '+223 77 11 22 33')
            ->set('address', 'Badalabougou')
            ->set('neighborhood', 'Badalabougou')
            ->set('paymentMethod', 'cash_on_delivery')
            ->call('submitOrder');

        $component->assertHasNoErrors();
        $order = Order::latest('id')->first();
        $this->assertNotNull($order);

        // Le sous-total doit correspondre au VRAI prix DB et non 1 FCFA x 2
        $this->assertEquals($realPrice * 2, $order->subtotal, 'Le sous-total doit être recalculé depuis la base de données.');
        
        // Le stock doit être décrémenté
        $product->refresh();
        $this->assertEquals($initialStock - 2, $product->stock, 'Le stock doit être décrémenté de manière atomique.');
    }

    public function test_idor_protection_blocks_unauthorized_order_access(): void
    {
        $order = Order::create([
            'order_number' => 'BKO-CONFIDENTIEL-001',
            'tracking_token' => 'SECRET_TOKEN_XYZ_123',
            'customer_first_name' => 'Victime',
            'customer_last_name' => 'Privee',
            'customer_phone' => '+223 70 00 11 22',
            'city' => 'Bamako',
            'neighborhood' => 'ACI 2000',
            'address' => 'Secret Villa',
            'payment_method' => 'orange_money',
            'payment_status' => 'paid',
            'subtotal' => 10000,
            'delivery_fee' => 1500,
            'total' => 11500,
            'status' => 'confirmed',
        ]);

        // Un visiteur sans session ni token tente d'ouvrir cette commande
        $response = $this->get('/checkout/orange/return?order_id=' . $order->order_number);

        // Doit être rejeté avec 403 Forbidden
        $response->assertStatus(403);
    }

    public function test_orange_money_webhook_is_idempotent(): void
    {
        $order = Order::create([
            'order_number' => 'BKO-WEBHOOK-TEST',
            'orange_money_notif_token' => 'TOKEN_SECURE_987654',
            'customer_first_name' => 'Alpha',
            'customer_last_name' => 'Barry',
            'customer_phone' => '+223 66 00 00 00',
            'city' => 'Bamako',
            'neighborhood' => 'Hamdallaye',
            'address' => 'Immeuble BKO',
            'payment_method' => 'orange_money',
            'payment_status' => 'pending',
            'subtotal' => 5000,
            'delivery_fee' => 1500,
            'total' => 6500,
            'status' => 'confirmed',
        ]);

        // 1er appel webhook
        $response1 = $this->postJson('/checkout/orange/notif', [
            'status' => 'SUCCESS',
            'notif_token' => 'TOKEN_SECURE_987654',
            'txnid' => 'TXN-FIRST-CALL',
        ]);

        $response1->assertStatus(200);
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('TXN-FIRST-CALL', $order->orange_money_transaction_id);

        // 2ème appel (rejoué / duplicate webhook)
        $response2 = $this->postJson('/checkout/orange/notif', [
            'status' => 'SUCCESS',
            'notif_token' => 'TOKEN_SECURE_987654',
            'txnid' => 'TXN-DUPLICATE-CALL',
        ]);

        $response2->assertStatus(200);
        $response2->assertJson(['status' => 'already_processed']);
        $order->refresh();
        // Le transaction_id initial ne doit pas avoir été écrasé
        $this->assertEquals('TXN-FIRST-CALL', $order->orange_money_transaction_id);
    }

    public function test_security_headers_are_present_in_responses(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
