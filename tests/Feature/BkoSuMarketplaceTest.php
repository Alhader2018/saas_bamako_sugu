<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BkoSuMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_loads_successfully_with_bko_su_brand(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Tout Bamako dans');
        $response->assertSee('un seul panier');
        $response->assertSee('Commander maintenant');
    }

    public function test_catalog_loads_successfully(): void
    {
        $response = $this->get('/catalogue');

        $response->assertStatus(200);
        $response->assertSee('Tous les rayons');
        $response->assertSee('Trier par');
    }

    public function test_product_detail_page_loads_with_fcfa_price(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->get('/produit/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('FCFA');
        $response->assertSee('Ajouter au panier');
        $response->assertSee('Orange Money');
    }

    public function test_checkout_page_loads_successfully(): void
    {
        $response = $this->get('/commander');

        $response->assertStatus(200);
        $response->assertSee('Finaliser');
    }

    public function test_admin_dashboard_loads_with_kpi_and_orders(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Tableau de bord');
        $response->assertSee('Commandes récentes');
        $response->assertSee('Chiffre', false);
    }

    public function test_admin_orders_index_loads_with_woocommerce_tabs_and_filters(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin/commandes');

        $response->assertStatus(200);
        $response->assertSee('Commandes');
        $response->assertSee('Actions groupées');
        $response->assertSee('Filtrer');
    }

    public function test_admin_order_detail_loads_successfully(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $order = \App\Models\Order::first();
        $this->assertNotNull($order);

        $response = $this->actingAs($admin)->get('/admin/commandes/' . $order->id);

        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('Articles de la commande');
        $response->assertSee('Informations de Paiement');
    }

    public function test_admin_products_index_loads_successfully(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin/produits');

        $response->assertStatus(200);
        $response->assertSee('Catalogue Produits');
        $response->assertSee('Ajouter un produit');
    }

    public function test_admin_stock_inventory_loads_successfully(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin/stock');

        $response->assertStatus(200);
        $response->assertSee('Inventaire & Stock');
        $response->assertSee('Unités physiques en rayon');
    }

    public function test_admin_customers_directory_loads_successfully(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin/clients');

        $response->assertStatus(200);
        $response->assertSee('Répertoire Clients');
        $response->assertSee('Clients uniques identifiés');
    }

    public function test_admin_payments_journal_loads_successfully(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin/paiements');

        $response->assertStatus(200);
        $response->assertSee('Paiements & Transactions');
        $response->assertSee('Orange Money');
    }

    public function test_admin_deliveries_loads_successfully(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin/livraisons');

        $response->assertStatus(200);
        $response->assertSee('Livraisons Bamako');
        $response->assertSee('À préparer en magasin');
    }

    public function test_admin_reports_loads_successfully(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin/rapports');

        $response->assertStatus(200);
        $response->assertSee('Rapports & Ventes', false);
        $response->assertSee('Chiffre', false);
    }

    public function test_admin_settings_loads_successfully(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get('/admin/parametres');

        $response->assertStatus(200);
        $response->assertSee('Paramètres du Supermarché');
        $response->assertSee('Orange Money Mali');
    }

    public function test_cart_service_calculates_totals_properly(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        CartService::clear();
        $this->assertEquals(0, CartService::count());

        CartService::add($product->id, 2);
        $this->assertEquals(2, CartService::count());
        $this->assertEquals($product->price * 2, CartService::subtotal());
        $this->assertTrue(CartService::total() > 0);

        CartService::clear();
        $this->assertEquals(0, CartService::count());
    }
}
