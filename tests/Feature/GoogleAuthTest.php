<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.google.client_id' => 'test-client-id.apps.googleusercontent.com',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect' => 'http://localhost:8000/auth/google/callback',
        ]);
    }

    public function test_google_redirect_initiates_oauth_flow(): void
    {
        $response = $this->get(route('auth.google'));

        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
        $this->assertStringContainsString('test-client-id.apps.googleusercontent.com', $response->headers->get('Location'));
    }

    public function test_google_callback_creates_customer_and_redirects_to_complete_profile_if_address_missing(): void
    {
        session()->put('google_oauth_state', 'test_state_123');

        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'mock_access_token_xyz',
                'token_type' => 'Bearer',
                'expires_in' => 3600,
            ], 200),
            'https://www.googleapis.com/oauth2/v3/userinfo' => Http::response([
                'sub' => 'google_uid_987654321',
                'email' => 'aminata.diarra@gmail.com',
                'name' => 'Aminata Diarra',
                'picture' => 'https://lh3.googleusercontent.com/a/avatar.jpg',
            ], 200),
        ]);

        $response = $this->get(route('auth.google.callback', [
            'code' => 'mock_auth_code',
            'state' => 'test_state_123',
        ]));

        $this->assertAuthenticated();

        $user = User::where('email', 'aminata.diarra@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Aminata Diarra', $user->name);
        $this->assertEquals('customer', $user->role);
        $this->assertEquals('google_uid_987654321', $user->google_id);
        $this->assertFalse($user->hasCompleteDeliveryProfile());

        // Redirection vers l'adresse de livraison
        $response->assertRedirect(route('profile.complete'));
    }

    public function test_complete_profile_updates_delivery_address_and_redirects_home(): void
    {
        $user = User::create([
            'name' => 'Aminata Diarra',
            'email' => 'aminata.diarra@gmail.com',
            'password' => bcrypt('password123'),
            'google_id' => 'google_uid_987654321',
            'role' => 'customer',
            'is_active' => true,
        ]);

        $this->assertFalse($user->hasCompleteDeliveryProfile());

        $response = $this->actingAs($user)->post(route('profile.complete.update'), [
            'city' => 'Bamako',
            'neighborhood' => 'ACI 2000',
            'address' => 'Près du Monument de l\'Indépendance, Immeuble A, Porte 12',
            'phone' => '+223 75 00 22 44',
        ]);

        $response->assertRedirect(route('home'));

        $user->refresh();
        $this->assertEquals('+223 75 00 22 44', $user->phone);
        $this->assertEquals('ACI 2000', $user->neighborhood);
        $this->assertEquals('Près du Monument de l\'Indépendance, Immeuble A, Porte 12', $user->address);
        $this->assertTrue($user->hasCompleteDeliveryProfile());
    }

    public function test_google_callback_with_complete_profile_redirects_directly_to_home(): void
    {
        $existingUser = User::create([
            'name' => 'Ousmane Coulibaly',
            'email' => 'ousmane.coulibaly@gmail.com',
            'password' => bcrypt('password123'),
            'google_id' => 'google_uid_existing_111',
            'phone' => '+223 66 11 22 33',
            'city' => 'Bamako',
            'neighborhood' => 'Badalabougou',
            'address' => 'Rue 12, Porte 4',
            'role' => 'customer',
            'is_active' => true,
        ]);

        $this->assertTrue($existingUser->hasCompleteDeliveryProfile());

        session()->put('google_oauth_state', 'test_state_456');

        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'mock_access_token_abc',
                'token_type' => 'Bearer',
                'expires_in' => 3600,
            ], 200),
            'https://www.googleapis.com/oauth2/v3/userinfo' => Http::response([
                'sub' => 'google_uid_existing_111',
                'email' => 'ousmane.coulibaly@gmail.com',
                'name' => 'Ousmane Coulibaly',
                'picture' => null,
            ], 200),
        ]);

        $response = $this->get(route('auth.google.callback', [
            'code' => 'mock_auth_code_2',
            'state' => 'test_state_456',
        ]));

        $this->assertAuthenticatedAs($existingUser);
        $response->assertRedirect(route('home'));
    }
}
