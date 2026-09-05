<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->regularUser = User::factory()->create([
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    public function test_guests_and_regular_users_cannot_access_categories_management(): void
    {
        // 1. Visiteur non connecté
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login'));

        // 2. Client régulier sans droit staff -> 403
        $response = $this->actingAs($this->regularUser)->get(route('admin.categories.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_categories_index(): void
    {
        Category::create([
            'name' => 'Épicerie Fine',
            'slug' => 'epicerie-fine',
            'icon' => 'shopping-cart',
            'badge' => 'Frais',
            'is_featured' => true,
            'display_order' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSee('Épicerie Fine');
        $response->assertSee('epicerie-fine');
        $response->assertSee('Frais');
    }

    public function test_admin_can_view_create_category_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.categories.create'));
        $response->assertStatus(200);
        $response->assertSee('Nouvelle Catégorie');
    }

    public function test_admin_can_create_a_category_with_auto_generated_slug(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.categories.store'), [
            'name' => 'Livres & Formations Numériques',
            'icon' => 'book-open',
            'badge' => 'Nouveau',
            'description' => 'Toutes nos ressources dématérialisées',
            'display_order' => 5,
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Livres & Formations Numériques',
            'slug' => 'livres-formations-numeriques',
            'icon' => 'book-open',
            'badge' => 'Nouveau',
            'display_order' => 5,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_upload_category_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('category_banner.jpg', 600, 400);

        $response = $this->actingAs($this->adminUser)->post(route('admin.categories.store'), [
            'name' => 'Mode & Accessoires',
            'slug' => 'mode-accessoires',
            'icon' => 'shirt',
            'image_file' => $file,
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $category = Category::where('slug', 'mode-accessoires')->first();
        $this->assertNotNull($category);
        $this->assertStringStartsWith('/storage/categories/', $category->image_url);

        $storedPath = str_replace('/storage/', '', $category->image_url);
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_admin_can_update_a_category(): void
    {
        $category = Category::create([
            'name' => 'Informatique',
            'slug' => 'informatique',
            'icon' => 'monitor',
            'display_order' => 2,
        ]);

        $response = $this->actingAs($this->adminUser)->put(route('admin.categories.update', $category), [
            'name' => 'Informatique & Tech',
            'slug' => 'informatique-et-tech',
            'icon' => 'monitor',
            'badge' => 'Top Vente',
            'display_order' => 1,
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Informatique & Tech',
            'slug' => 'informatique-et-tech',
            'badge' => 'Top Vente',
            'display_order' => 1,
        ]);
    }

    public function test_cannot_delete_category_with_products(): void
    {
        $category = Category::create([
            'name' => 'Boissons',
            'slug' => 'boissons',
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Jus de Bissap',
            'slug' => 'jus-de-bissap',
            'reference' => 'REF-BIS-01',
            'price' => 1000,
            'stock' => 50,
            'product_type' => 'physical',
        ]);

        $response = $this->actingAs($this->adminUser)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');

        // La catégorie ne doit pas être supprimée
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_can_delete_empty_category(): void
    {
        $category = Category::create([
            'name' => 'Catégorie Vide',
            'slug' => 'categorie-vide',
        ]);

        $response = $this->actingAs($this->adminUser)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
