<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DigitalProductDownload;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\User;
use App\Services\CartService;
use App\Mail\DigitalProductAccessMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DigitalProductTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'E-books & Formations',
            'slug' => 'ebooks-formations',
        ]);
    }

    public function test_product_model_detects_digital_and_physical_types(): void
    {
        $physicalProduct = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Riz parfumé 50kg',
            'slug' => 'riz-parfume-50kg',
            'reference' => 'BKO-RIZ-001',
            'price' => 25000,
            'stock' => 10,
            'product_type' => 'physical',
            'description' => 'Sac de riz',
            'image_url' => 'https://example.com/riz.jpg',
        ]);

        $digitalProduct = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Guide du Freelance à Bamako',
            'slug' => 'guide-du-freelance-a-bamako',
            'reference' => 'BKO-DIG-001',
            'price' => 5000,
            'stock' => 9999,
            'product_type' => 'digital',
            'digital_type' => 'ebook',
            'access_type' => 'file_download',
            'download_limit' => 3,
            'description' => 'Guide complet PDF',
            'image_url' => 'https://example.com/guide.jpg',
        ]);

        $this->assertTrue($physicalProduct->isPhysical());
        $this->assertFalse($physicalProduct->isDigital());

        $this->assertTrue($digitalProduct->isDigital());
        $this->assertFalse($digitalProduct->isPhysical());
        $this->assertEquals('E-book / Livre numérique', $digitalProduct->digital_type_label);
    }

    public function test_admin_can_create_digital_product_without_image_url(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Prepa ENA culture générale',
            'category_id' => $this->category->id,
            'product_type' => 'digital',
            'digital_type' => 'pdf',
            'access_type' => 'file_download',
            'download_limit' => 5,
            'price' => 5000,
            // image_url et description intentionnellement omis
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Prepa ENA culture générale',
            'product_type' => 'digital',
            'digital_type' => 'pdf',
            'price' => 5000,
        ]);

        $createdProduct = Product::where('name', 'Prepa ENA culture générale')->first();
        $this->assertNotNull($createdProduct->image_url);
    }

    public function test_cart_service_handles_digital_quantities_and_zero_delivery_fee(): void
    {
        Session::start();
        CartService::clear();

        $digitalProduct = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Guide PDF',
            'slug' => 'guide-pdf',
            'reference' => 'REF-GUIDE',
            'price' => 5000,
            'stock' => 9999,
            'product_type' => 'digital',
            'digital_type' => 'pdf',
            'description' => 'Guide test',
            'image_url' => 'https://example.com/test.jpg',
        ]);

        // 1. Ajout dans le panier : la quantité initiale est 1
        CartService::add($digitalProduct->id, 1);
        $this->assertEquals(1, CartService::count());
        $this->assertTrue(CartService::isPurelyDigital());
        $this->assertEquals(0, CartService::deliveryFee()); // Livraison offerte car 100% numérique
        $this->assertEquals(5000, CartService::total());

        // 2. Tenter d'ajouter à nouveau le produit : la quantité reste plafonnée à 1
        CartService::add($digitalProduct->id, 1);
        $this->assertEquals(1, CartService::count());

        // 3. Ajout d'un produit physique dans le même panier (commande mixte)
        $physicalProduct = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Thé vert de Chine',
            'slug' => 'the-vert',
            'reference' => 'REF-THE',
            'price' => 2000,
            'stock' => 15,
            'product_type' => 'physical',
            'description' => 'Boîte de thé',
            'image_url' => 'https://example.com/the.jpg',
        ]);

        CartService::add($physicalProduct->id, 1);

        $this->assertEquals(2, CartService::count());
        $this->assertFalse(CartService::isPurelyDigital());
        $this->assertTrue(CartService::hasDigitalItems());
        $this->assertTrue(CartService::hasPhysicalItems());

        // Frais de livraison standard appliqué (1 500 FCFA) car il y a au moins un produit physique < 50 000 FCFA
        $this->assertEquals(1500, CartService::deliveryFee());
        $this->assertEquals(8500, CartService::total()); // 5000 + 2000 + 1500
    }

    public function test_secure_download_controller_protects_files_and_audits(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Livre PDF Exclusif',
            'slug' => 'livre-pdf-exclusif',
            'reference' => 'REF-LIVRE-PDF',
            'price' => 10000,
            'stock' => 9999,
            'product_type' => 'digital',
            'download_limit' => 2,
            'description' => 'Test',
            'image_url' => 'https://example.com/livre.jpg',
        ]);

        $filePath = 'digital_products/mon-livre.pdf';
        Storage::disk('local')->put($filePath, 'Contenu privé du livre');

        $file = ProductFile::create([
            'product_id' => $product->id,
            'name' => 'Livre Complet PDF',
            'file_path' => $filePath,
            'file_name' => 'mon-livre.pdf',
            'file_size' => 1024,
            'mime_type' => 'application/pdf',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'BKO-TEST-123',
            'tracking_token' => 'secret-token-12345',
            'customer_first_name' => 'Oumar',
            'customer_last_name' => 'Diallo',
            'customer_phone' => '+223 70 00 00 00',
            'customer_email' => 'oumar@test.ml',
            'city' => 'Bamako',
            'neighborhood' => 'En ligne',
            'address' => 'Livraison numérique',
            'payment_method' => 'orange_money',
            'payment_status' => 'pending', // NON ENCORE PAYÉ
            'subtotal' => 10000,
            'delivery_fee' => 0,
            'discount' => 0,
            'total' => 10000,
            'status' => 'confirmed',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_type' => 'digital',
            'product_name' => $product->name,
            'product_image' => $product->image_url,
            'price' => 10000,
            'quantity' => 1,
            'total' => 10000,
        ]);

        // 1. Tentative de téléchargement par un inconnu non authentifié : 403 Forbidden
        $response = $this->get(route('digital.download', [
            'orderNumber' => $order->order_number,
            'fileId' => $file->id,
        ]));
        $response->assertStatus(403);

        // 2. Utilisateur connecté mais commande NON PAYÉE : 402 Payment Required
        $response = $this->actingAs($user)->get(route('digital.download', [
            'orderNumber' => $order->order_number,
            'fileId' => $file->id,
        ]));
        $response->assertStatus(402);

        // 3. Passage de la commande à 'paid' (paiement Orange Money confirmé)
        $order->update(['payment_status' => 'paid']);

        // Téléchargement réussi #1
        $response = $this->actingAs($user)->get(route('digital.download', [
            'orderNumber' => $order->order_number,
            'fileId' => $file->id,
        ]));
        $response->assertStatus(200);

        // Vérifier l'enregistrement d'audit
        $this->assertDatabaseHas('digital_product_downloads', [
            'order_id' => $order->id,
            'product_file_id' => $file->id,
            'user_id' => $user->id,
        ]);

        // Téléchargement réussi #2 (atteint la limite de 2)
        $response = $this->actingAs($user)->get(route('digital.download', [
            'orderNumber' => $order->order_number,
            'fileId' => $file->id,
        ]));
        $response->assertStatus(200);

        // Téléchargement #3 : doit être rejeté car limite de 2 atteinte (403)
        $response = $this->actingAs($user)->get(route('digital.download', [
            'orderNumber' => $order->order_number,
            'fileId' => $file->id,
        ]));
        $response->assertStatus(403);
    }

    public function test_buy_now_adds_digital_product_to_cart_and_redirects_to_checkout(): void
    {
        $category = Category::create([
            'name' => 'E-books Test BuyNow',
            'slug' => 'ebooks-test-buynow',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Guide Réussite Concours',
            'slug' => 'guide-reussite-concours',
            'reference' => 'BKO-BUYNOW-001',
            'price' => 7500,
            'stock' => 9999,
            'product_type' => 'digital',
            'digital_type' => 'pdf',
            'access_type' => 'file_download',
            'image_url' => 'https://example.com/guide.jpg',
            'description' => 'Guide test',
        ]);

        // Clic sur "Acheter et télécharger" -> POST /panier/acheter/{product}
        $response = $this->post(route('cart.buy-now', $product), [
            'quantity' => 1,
        ]);

        // Doit rediriger immédiatement vers la page de commande (/commander)
        $response->assertRedirect(route('checkout'));

        // Le panier doit contenir le produit avec une quantité de 1
        $cart = session()->get('bko_cart');
        $this->assertIsArray($cart);
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(1, $cart[$product->id]['quantity']);
        $this->assertEquals(7500, $cart[$product->id]['price']);

        // Visite de la page checkout : le produit est bien présent
        $checkoutResponse = $this->get(route('checkout'));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Guide Réussite Concours');
        $checkoutResponse->assertDontSee('Votre panier est vide');
    }

    public function test_paying_digital_order_automatically_sends_email_with_download_link(): void
    {
        Mail::fake();
        Storage::fake('local');

        $category = Category::create([
            'name' => 'E-books PDF',
            'slug' => 'ebooks-pdf',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Formation Laravel 11 BKO',
            'slug' => 'formation-laravel-11-bko',
            'reference' => 'BKO-LRV-001',
            'price' => 15000,
            'stock' => 9999,
            'product_type' => 'digital',
            'digital_type' => 'course',
            'access_type' => 'file_download',
            'description' => 'Formation complète',
        ]);

        $file = ProductFile::create([
            'product_id' => $product->id,
            'name' => 'Guide Complet Laravel',
            'file_name' => 'Guide_Complet_Laravel.pdf',
            'file_path' => 'digital_products/guide.pdf',
            'file_size' => 2048576,
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
        ]);

        $order = Order::create([
            'order_number' => 'BKO-TEST-MAIL-001',
            'tracking_token' => 'TOKEN_MAIL_123',
            'customer_first_name' => 'Amadou',
            'customer_last_name' => 'Coulibaly',
            'customer_phone' => '+223 70 11 22 33',
            'customer_email' => 'amadou.coulibaly@test.ml',
            'city' => 'Bamako',
            'neighborhood' => 'En ligne',
            'address' => 'En ligne',
            'payment_method' => 'orange_money',
            'payment_status' => 'pending',
            'subtotal' => 15000,
            'delivery_fee' => 0,
            'total' => 15000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_type' => 'digital',
            'product_name' => $product->name,
            'price' => 15000,
            'quantity' => 1,
            'total' => 15000,
        ]);

        // Aucun email n'est envoyé tant que la commande est en attente
        Mail::assertNothingSent();

        // Le paiement est validé (ex: retour Orange Money ou confirmation admin)
        $order->update(['payment_status' => 'paid']);

        // Un email contenant le lien de téléchargement doit avoir été expédié
        Mail::assertSent(DigitalProductAccessMail::class, function ($mail) use ($order, $file) {
            $this->assertTrue($mail->hasTo('amadou.coulibaly@test.ml'));
            $renderedHtml = $mail->render();
            // L'email doit contenir le numéro de commande, le nom du fichier et le lien de téléchargement
            $this->assertStringContainsString($order->order_number, $renderedHtml);
            $this->assertStringContainsString('Guide_Complet_Laravel.pdf', $renderedHtml);
            $expectedDownloadUrl = route('digital.download', [
                'orderNumber' => $order->order_number,
                'fileId' => $file->id,
            ]);
            $this->assertStringContainsString($expectedDownloadUrl, $renderedHtml);

            return true;
        });
    }

    public function test_admin_can_manually_resend_download_links_by_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Ebook Recettes Maliennes',
            'slug' => 'ebook-recettes-maliennes',
            'reference' => 'BKO-REC-001',
            'price' => 5000,
            'stock' => 9999,
            'product_type' => 'digital',
            'digital_type' => 'ebook',
            'access_type' => 'file_download',
            'description' => 'Recettes',
        ]);

        $order = Order::create([
            'order_number' => 'BKO-RESEND-001',
            'tracking_token' => 'TOKEN_RESEND_XYZ',
            'customer_first_name' => 'Fatoumata',
            'customer_last_name' => 'Diarra',
            'customer_phone' => '+223 75 00 00 00',
            'customer_email' => 'fatou@test.ml',
            'city' => 'Bamako',
            'neighborhood' => 'En ligne',
            'address' => 'En ligne',
            'payment_method' => 'orange_money',
            'payment_status' => 'paid',
            'subtotal' => 5000,
            'delivery_fee' => 0,
            'total' => 5000,
            'status' => 'confirmed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_type' => 'digital',
            'product_name' => $product->name,
            'price' => 5000,
            'quantity' => 1,
            'total' => 5000,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.resend-digital', $order));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertSent(DigitalProductAccessMail::class, function ($mail) {
            return $mail->hasTo('fatou@test.ml');
        });
    }
}


