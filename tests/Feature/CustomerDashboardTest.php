<?php

namespace Tests\Feature;

use App\Models\CustomerAddress;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login_when_accessing_account(): void
    {
        $response = $this->get(route('account.dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_customer_can_view_dashboard_with_counters(): void
    {
        $customer = User::where('role', 'customer')->first();

        // Créer une commande en cours pour ce client
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'BKO-2026-TEST01',
            'customer_first_name' => 'Fanta',
            'customer_last_name' => 'Keita',
            'customer_phone' => '+223 70 11 22 33',
            'city' => 'Bamako',
            'neighborhood' => 'ACI 2000',
            'address' => 'Rue 12, Porte 4',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'status' => 'in_preparation',
            'subtotal' => 25000,
            'delivery_fee' => 1500,
            'total' => 26500,
        ]);

        $product = Product::first();
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'total' => $product->price,
        ]);

        // Ajouter un favori
        Favorite::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($customer)->get(route('account.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Tableau de bord');
        $response->assertSee('Mes commandes');
        $response->assertSee('BKO-2026-TEST01');
        $response->assertSee('En préparation');
        $response->assertSee('Suivre ma commande');
    }

    public function test_empty_state_displayed_when_user_has_no_orders(): void
    {
        $newCustomer = User::create([
            'name' => 'Nouveau Client',
            'email' => 'nouveau@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($newCustomer)->get(route('account.dashboard'));

        $response->assertStatus(200);
        $response->assertSee("Vous n'avez pas encore de commande", false);
        $response->assertSee("Découvrir les produits");
    }

    public function test_customer_cannot_view_another_customer_order(): void
    {
        $customerA = User::create([
            'name' => 'Client A',
            'email' => 'clientA@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $customerB = User::create([
            'name' => 'Client B',
            'email' => 'clientB@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $orderOfB = Order::create([
            'user_id' => $customerB->id,
            'order_number' => 'BKO-SECRET-B',
            'customer_first_name' => 'Client',
            'customer_last_name' => 'B',
            'customer_phone' => '+223 70 99 88 77',
            'city' => 'Bamako',
            'neighborhood' => 'Hamdallaye ACI',
            'address' => 'Secret Rue',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'status' => 'confirmed',
            'subtotal' => 20000,
            'delivery_fee' => 1500,
            'total' => 21500,
        ]);

        // Client A tente d'accéder à la commande de Client B
        $response = $this->actingAs($customerA)->get(route('account.orders.show', $orderOfB));

        // Doit impérativement refuser l'accès (403 Forbidden)
        $response->assertStatus(403);
    }

    public function test_customer_can_view_their_own_order_detail_with_timeline(): void
    {
        $customer = User::where('role', 'customer')->first();

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'BKO-DETAIL-001',
            'customer_first_name' => 'Adama',
            'customer_last_name' => 'Coulibaly',
            'customer_phone' => '+223 77 22 33 44',
            'city' => 'Bamako',
            'neighborhood' => 'Badalabougou',
            'address' => 'Près du Palais de la Culture',
            'delivery_notes' => 'Appeler 10min avant',
            'payment_method' => 'orange_money',
            'payment_status' => 'paid',
            'status' => 'in_delivery',
            'subtotal' => 35000,
            'delivery_fee' => 1500,
            'total' => 36500,
        ]);

        $product = Product::first();
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 2,
            'total' => $product->price * 2,
        ]);

        $response = $this->actingAs($customer)->get(route('account.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('BKO-DETAIL-001');
        $response->assertSee('Suivi de votre commande');
        $response->assertSee('Badalabougou');
        $response->assertSee('Près du Palais de la Culture');
        $response->assertSee('Appeler 10min avant');
    }

    public function test_customer_can_cancel_pending_order_and_stock_is_restored(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();
        $initialStock = $product->stock;

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'BKO-CANCEL-001',
            'customer_first_name' => 'Adama',
            'customer_last_name' => 'Coulibaly',
            'customer_phone' => '+223 77 22 33 44',
            'city' => 'Bamako',
            'neighborhood' => 'Badalabougou',
            'address' => 'Rue 12',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'status' => 'pending',
            'subtotal' => 10000,
            'delivery_fee' => 1500,
            'total' => 11500,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 3,
            'total' => $product->price * 3,
        ]);

        $this->assertTrue($order->isCancellable());

        $response = $this->actingAs($customer)->post(route('account.orders.cancel', $order));

        $response->assertStatus(302);
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);

        // Vérification de la restauration du stock
        $product->refresh();
        $this->assertEquals($initialStock + 3, $product->stock);
    }

    public function test_customer_can_toggle_favorites_and_view_favorites_page(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();

        // 1. Ajouter aux favoris
        $response = $this->actingAs($customer)->postJson(route('account.favorites.toggle', $product));
        $response->assertStatus(200);
        $response->assertJson(['favorited' => true]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
        ]);

        // 2. Afficher la page des favoris
        $pageResponse = $this->actingAs($customer)->get(route('account.favorites.index'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee($product->name);

        // 3. Retirer des favoris
        $response2 = $this->actingAs($customer)->postJson(route('account.favorites.toggle', $product));
        $response2->assertStatus(200);
        $response2->assertJson(['favorited' => false]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_customer_can_add_and_manage_addresses(): void
    {
        $customer = User::where('role', 'customer')->first();

        // Ajouter une première adresse
        $response = $this->actingAs($customer)->post(route('account.addresses.store'), [
            'label' => 'Bureau ACI',
            'recipient_name' => 'Ousmane Traore',
            'phone' => '+223 76 55 44 33',
            'neighborhood' => 'Hamdallaye ACI',
            'commune' => 'Commune IV',
            'address' => 'Immeuble BMS, 3ème étage',
            'delivery_notes' => 'Déposer à l\'accueil',
            'is_default' => 1,
        ]);

        $response->assertStatus(302);

        $address = CustomerAddress::where('user_id', $customer->id)->first();
        $this->assertNotNull($address);
        $this->assertEquals('Bureau ACI', $address->label);
        $this->assertEquals('Hamdallaye ACI', $address->neighborhood);
        $this->assertTrue($address->is_default);

        // Afficher la page des adresses
        $pageResponse = $this->actingAs($customer)->get(route('account.addresses.index'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Bureau ACI');
        $pageResponse->assertSee('Immeuble BMS, 3ème étage');
    }

    public function test_customer_can_update_profile_and_password(): void
    {
        $customer = User::create([
            'name' => 'Ancien Nom',
            'email' => 'profil.test@gmail.com',
            'password' => bcrypt('ancienpass123'),
            'phone' => '+223 70 00 00 11',
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Modification profil
        $response = $this->actingAs($customer)->put(route('account.profile.update'), [
            'name' => 'Nouveau Nom Vérifié',
            'email' => 'profil.test@gmail.com',
            'phone' => '+223 71 22 33 44',
            'neighborhood' => 'Hippodrome',
            'address' => 'Rue 21, Porte 8',
        ]);

        $response->assertStatus(302);
        $customer->refresh();
        $this->assertEquals('Nouveau Nom Vérifié', $customer->name);
        $this->assertEquals('+223 71 22 33 44', $customer->phone);
        $this->assertEquals('Hippodrome', $customer->neighborhood);

        // Modification mot de passe
        $pwdResponse = $this->actingAs($customer)->put(route('account.profile.password'), [
            'current_password' => 'ancienpass123',
            'password' => 'NouveauPass456',
            'password_confirmation' => 'NouveauPass456',
        ]);

        $pwdResponse->assertStatus(302);
    }
}
