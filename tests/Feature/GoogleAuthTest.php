<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_google_redirect_initiates_oauth_flow(): void
    {
        $response = $this->get(route('auth.google'));

        // Socialite redirige vers Google Accounts OAuth
        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_google_callback_creates_customer_and_redirects_to_complete_profile_if_address_missing(): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google_uid_987654321');
        $socialiteUser->shouldReceive('getEmail')->andReturn('aminata.diarra@gmail.com');
        $socialiteUser->shouldReceive('getName')->andReturn('Aminata Diarra');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/a/avatar.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticated();

        $user = User::where('email', 'aminata.diarra@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Aminata Diarra', $user->name);
        $this->assertEquals('customer', $user->role);
        $this->assertEquals('google_uid_987654321', $user->google_id);
        $this->assertFalse($user->hasCompleteDeliveryProfile());

        // Doit impérativement rediriger vers la saisie de l'adresse de livraison
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

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google_uid_existing_111');
        $socialiteUser->shouldReceive('getEmail')->andReturn('ousmane.coulibaly@gmail.com');
        $socialiteUser->shouldReceive('getName')->andReturn('Ousmane Coulibaly');
        $socialiteUser->shouldReceive('getAvatar')->andReturn(null);

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticatedAs($existingUser);
        // Le profil étant déjà complet, pas besoin de redemander l'adresse
        $response->assertRedirect(route('home'));
    }
}
