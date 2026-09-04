<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
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
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Tableau de bord Administration');
        $response->assertSee('Commandes Récentes');
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
