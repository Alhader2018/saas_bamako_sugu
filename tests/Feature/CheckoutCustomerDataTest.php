<?php

namespace Tests\Feature;

use App\Livewire\CheckoutForm;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutCustomerDataTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name' => 'Alimentation',
            'slug' => 'alimentation',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Riz Gambi 50kg',
            'slug' => 'riz-gambi-50kg',
            'reference' => 'REF-RIZ-001',
            'price' => 25000,
            'stock' => 10,
            'product_type' => 'physical',
            'is_active' => true,
        ]);

        CartService::clear();
        CartService::add($this->product->id, 1);
    }

    public function test_authenticated_user_profile_data_is_loaded_into_checkout_form(): void
    {
        $user = User::factory()->create([
            'name' => 'Amadou Coulibaly',
            'email' => 'amadou@example.com',
            'phone' => '+223 76 11 22 33',
            'city' => 'Bamako',
            'neighborhood' => 'Hamdallaye ACI',
            'address' => 'Rue 312, Porte 15',
        ]);

        Livewire::actingAs($user)
            ->test(CheckoutForm::class)
            ->assertSet('firstName', 'Amadou')
            ->assertSet('lastName', 'Coulibaly')
            ->assertSet('phone', '+223 76 11 22 33')
            ->assertSet('email', 'amadou@example.com')
            ->assertSet('city', 'Bamako')
            ->assertSet('neighborhood', 'Hamdallaye ACI')
            ->assertSet('address', 'Rue 312, Porte 15')
            ->assertSet('orangeMoneyNumber', '+223 76 11 22 33')
            ->assertSet('loadedFromCustomerProfile', true)
            ->assertSee('Coordonnées pré-remplies directement depuis votre fiche client');
    }

    public function test_guest_entering_known_phone_retrieves_customer_sheet_data(): void
    {
        // Créer une commande précédente pour ce numéro
        Order::create([
            'order_number' => 'BKO-TEST-12345',
            'tracking_token' => 'test-token-123',
            'customer_first_name' => 'Fatoumata',
            'customer_last_name' => 'Diarra',
            'customer_phone' => '+223 66 99 88 77',
            'customer_email' => 'fatou@example.ml',
            'city' => 'Bamako',
            'neighborhood' => 'Badalabougou',
            'address' => 'Près du palais de la culture',
            'payment_method' => 'orange_money',
            'orange_money_number' => '+223 66 99 88 77',
            'subtotal' => 25000,
            'delivery_fee' => 1500,
            'discount' => 0,
            'total' => 26500,
            'status' => 'confirmed',
        ]);

        Livewire::test(CheckoutForm::class)
            ->set('phone', '+223 66 99 88 77')
            ->assertSet('firstName', 'Fatoumata')
            ->assertSet('lastName', 'Diarra')
            ->assertSet('email', 'fatou@example.ml')
            ->assertSet('neighborhood', 'Badalabougou')
            ->assertSet('address', 'Près du palais de la culture')
            ->assertSet('orangeMoneyNumber', '+223 66 99 88 77')
            ->assertSet('loadedFromCustomerProfile', true);
    }

    public function test_orange_money_number_synchronizes_automatically_with_phone(): void
    {
        Livewire::test(CheckoutForm::class)
            ->set('phone', '+223 70 00 11 22')
            ->assertSet('orangeMoneyNumber', '+223 70 00 11 22')
            ->set('phone', '+223 75 55 44 33')
            ->assertSet('orangeMoneyNumber', '+223 75 55 44 33');
    }

    public function test_user_can_opt_to_pay_with_different_orange_money_number(): void
    {
        Livewire::test(CheckoutForm::class)
            ->set('phone', '+223 76 00 11 22')
            ->set('useDifferentPaymentNumber', true)
            ->set('orangeMoneyNumber', '+223 65 99 88 77')
            ->set('phone', '+223 76 99 99 99')
            // Le numéro Orange Money ne doit pas être écrasé quand useDifferentPaymentNumber est true
            ->assertSet('orangeMoneyNumber', '+223 65 99 88 77');
    }

    public function test_order_submission_succeeds_using_customer_phone_directly_for_payment(): void
    {
        Livewire::test(CheckoutForm::class)
            ->set('firstName', 'Sekou')
            ->set('lastName', 'Keita')
            ->set('phone', '+223 77 12 34 56')
            ->set('email', 'sekou@example.ml')
            ->set('city', 'Bamako')
            ->set('neighborhood', 'ACI 2000')
            ->set('address', 'Rue 12, Porte 4')
            ->set('paymentMethod', 'orange_money')
            ->call('submitOrder')
            ->assertHasNoErrors()
            ->assertSet('orderCompleted', true);

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('Sekou', $order->customer_first_name);
        $this->assertEquals('+223 77 12 34 56', $order->customer_phone);
        $this->assertEquals('+223 77 12 34 56', $order->orange_money_number);
    }
}
