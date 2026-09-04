<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleAuthService
{
    private const AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const USERINFO_URL = 'https://www.googleapis.com/oauth2/v3/userinfo';

    public static function isConfigured(): bool
    {
        return !empty(config('services.google.client_id')) && !empty(config('services.google.client_secret'));
    }

    public static function getRedirectUrl(): string
    {
        $state = Str::random(40);
        session()->put('google_oauth_state', $state);

        $params = [
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'access_type' => 'online',
            'state' => $state,
            'prompt' => 'select_account',
        ];

        return self::AUTH_URL . '?' . http_build_query($params);
    }

    public static function getUserByCode(string $code, ?string $state = null): ?array
    {
        // Vérification de sécurité anti-CSRF sur le state OAuth
        $expectedState = session()->pull('google_oauth_state');
        if ($expectedState && $state && !hash_equals($expectedState, $state)) {
            Log::warning('Google OAuth state mismatch (CSRF protection)');
            return null;
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        try {
            $tokenResponse = Http::asForm()->post(self::TOKEN_URL, [
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Erreur échange de token Google OAuth: ' . $tokenResponse->body());
                return null;
            }

            $accessToken = $tokenResponse->json('access_token');
            if (!$accessToken) {
                return null;
            }

            $userResponse = Http::withToken($accessToken)->get(self::USERINFO_URL);
            if (!$userResponse->successful()) {
                Log::error('Erreur récupération profil Google OAuth: ' . $userResponse->body());
                return null;
            }

            $userData = $userResponse->json();

            return [
                'id' => $userData['sub'] ?? null,
                'email' => $userData['email'] ?? null,
                'name' => $userData['name'] ?? null,
                'avatar' => $userData['picture'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Exception Google OAuth: ' . $e->getMessage());
            return null;
        }
    }
}
