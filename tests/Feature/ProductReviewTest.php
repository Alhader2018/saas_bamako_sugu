<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name' => 'Épicerie',
            'slug' => 'epicerie',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Thé vert de Chine 250g',
            'slug' => 'the-vert-de-chine-250g',
            'reference' => 'THE-001',
            'price' => 2000,
            'stock' => 50,
            'product_type' => 'physical',
            'is_active' => true,
            'description' => '<p>Délicieux thé vert <strong>Gunpowder</strong> supérieur.</p>',
            'rating' => 5.0,
            'reviews_count' => 0,
        ]);
    }

    public function test_product_detail_page_displays_html_description_and_reviews_section(): void
    {
        $response = $this->get(route('product.show', $this->product->slug));

        $response->assertStatus(200);
        $response->assertSee('Gunpowder', false);
        $response->assertSee('Avis (0)');
        $response->assertSee('Donner mon avis');
    }

    public function test_guest_can_submit_valid_product_review(): void
    {
        $response = $this->post(route('product.review.store', $this->product), [
            'rating' => 4,
            'customer_name' => 'Ousmane Sangaré',
            'customer_email' => 'ousmane@example.com',
            'comment' => 'Très bon thé, arôme intense et livraison rapide.',
        ]);

        $response->assertRedirect(route('product.show', $this->product->slug) . '#tab-reviews');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => $this->product->id,
            'customer_name' => 'Ousmane Sangaré',
            'rating' => 4,
            'comment' => 'Très bon thé, arôme intense et livraison rapide.',
        ]);

        $this->product->refresh();
        $this->assertEquals(1, $this->product->reviews_count);
        $this->assertEquals(4.0, $this->product->rating);
    }

    public function test_review_submission_requires_valid_rating_and_comment(): void
    {
        $response = $this->from(route('product.show', $this->product->slug))->post(route('product.review.store', $this->product), [
            'rating' => 6, // Invalide (> 5)
            'customer_name' => '',
            'comment' => 'No', // Invalide (< 5 chars)
        ]);

        $response->assertSessionHasErrors(['rating', 'customer_name', 'comment']);
        $this->assertEquals(0, ProductReview::count());
    }

    public function test_multiple_reviews_correctly_calculate_average_rating(): void
    {
        $this->post(route('product.review.store', $this->product), [
            'rating' => 5,
            'customer_name' => 'Client 1',
            'comment' => 'Excellent produit rien à redire.',
        ]);

        $this->post(route('product.review.store', $this->product), [
            'rating' => 3,
            'customer_name' => 'Client 2',
            'comment' => 'Bien mais un peu cher.',
        ]);

        $this->product->refresh();
        $this->assertEquals(2, $this->product->reviews_count);
        $this->assertEquals(4.0, $this->product->rating); // (5+3)/2 = 4.0
    }

    public function test_verified_purchase_badge_is_awarded_if_user_ordered_the_product(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'BKO-REV-001',
            'tracking_token' => 'token-rev-001',
            'customer_first_name' => 'Moussa',
            'customer_last_name' => 'Traoré',
            'customer_phone' => '+223 76 00 00 01',
            'city' => 'Bamako',
            'neighborhood' => 'ACI 2000',
            'address' => 'Porte 1',
            'payment_method' => 'orange_money',
            'payment_status' => 'paid',
            'subtotal' => 2000,
            'delivery_fee' => 1500,
            'discount' => 0,
            'total' => 3500,
            'status' => 'delivered',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'price' => 2000,
            'quantity' => 1,
            'total' => 2000,
        ]);

        $this->actingAs($user)->post(route('product.review.store', $this->product), [
            'rating' => 5,
            'customer_name' => 'Moussa Traoré',
            'comment' => 'Produit authentique commandé la semaine dernière.',
        ]);

        $review = ProductReview::first();
        $this->assertNotNull($review);
        $this->assertTrue($review->is_verified_purchase);
    }

    public function test_admin_can_delete_inappropriate_review(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'is_active' => true]);

        $review = ProductReview::create([
            'product_id' => $this->product->id,
            'customer_name' => 'Spam Bot',
            'rating' => 1,
            'comment' => 'Spam comment to be removed.',
            'is_approved' => true,
        ]);

        $this->product->refresh();
        $this->assertEquals(1, $this->product->reviews_count);

        $response = $this->actingAs($admin)->delete(route('admin.products.reviews.destroy', $review));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('product_reviews', ['id' => $review->id]);

        $this->product->refresh();
        $this->assertEquals(0, $this->product->reviews_count);
    }
}
