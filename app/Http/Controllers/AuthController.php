<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isStaff() ? redirect()->route('admin.dashboard') : redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = strtolower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Trop de tentatives de connexion. Veuillez réessayer dans {$seconds} secondes.")
                ->withInput($request->only('email'));
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('error', 'Ce compte a été suspendu ou désactivé.');
            }

            // Régénération de session contre les attaques par fixation de session
            $request->session()->regenerate();

            if ($user->isStaff()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', "Bienvenue, {$user->name} !");
            }

            return redirect()->intended(route('home'))->with('success', "Connexion réussie. Bienvenue {$user->name} !");
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->with('error', 'Identifiants invalides ou mot de passe incorrect.')
            ->withInput($request->only('email'));
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|min:8|max:20',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'phone.required' => 'Le numéro de téléphone est requis.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Création stricte avec rôle customer (pas d'injection de rôle)
        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Votre compte BKO SU a été créé avec succès.');
    }

    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Erreur lors de la connexion avec Google. Veuillez réessayer.');
        }

        $email = strtolower(trim($googleUser->getEmail() ?? ''));
        if (empty($email)) {
            return redirect()->route('login')->with('error', 'Impossible de récupérer votre adresse email Google.');
        }

        // Recherche par google_id ou par email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            $updates = [];
            if (empty($user->google_id)) {
                $updates['google_id'] = $googleUser->getId();
            }
            if (empty($user->avatar) && $googleUser->getAvatar()) {
                $updates['avatar'] = $googleUser->getAvatar();
            }
            if (!empty($updates)) {
                $user->update($updates);
            }
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Client BKO SU',
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(\Illuminate\Support\Str::random(32)),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        if (!$user->is_active) {
            return redirect()->route('login')->with('error', 'Ce compte a été suspendu ou désactivé.');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->isStaff()) {
            return redirect()->intended(route('admin.dashboard'))->with('success', "Bienvenue, {$user->name} !");
        }

        // Si le profil de livraison n'est pas encore complété (adresse/quartier/téléphone)
        if (!$user->hasCompleteDeliveryProfile()) {
            return redirect()->route('profile.complete')->with('info', "Bienvenue {$user->name} ! Merci de renseigner votre adresse de livraison à Bamako.");
        }

        return redirect()->intended(route('home'))->with('success', "Connexion réussie avec votre compte Google !");
    }

    public function showCompleteProfileForm()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $neighborhoods = [
            'ACI 2000',
            'Badalabougou',
            'Hamdallaye ACI',
            'Hamdallaye',
            'Hippodrome',
            'Hippodrome II',
            'Faladié',
            'Baco Djicoroni ACI',
            'Baco Djicoroni Golf',
            'Torokorobougou',
            'Daoudabougou',
            'Sogoniko',
            'Magnambougou',
            'Yirimadio',
            'Banankabougou',
            'Kalaban Coura',
            'Kalaban Coro',
            'Sébénikoro',
            'Djicoroni Para',
            'Lafiabougou',
            'Dravela',
            'Quinzambougou',
            'Niaréla',
            'Bagadadji',
            'Médina Coura',
            'Missira',
            'Korofina Nord',
            'Korofina Sud',
            'Sotuba',
            'Moribabougou',
        ];

        return view('auth.complete-profile', compact('user', 'neighborhoods'));
    }

    public function updateCompleteProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'phone' => 'required|string|min:8|max:25',
            'neighborhood' => 'required|string|max:100',
            'address' => 'required|string|min:4|max:500',
            'city' => 'nullable|string|max:100',
        ], [
            'phone.required' => 'Le numéro de téléphone malien (+223) est obligatoire.',
            'neighborhood.required' => 'Veuillez sélectionner votre quartier à Bamako.',
            'address.required' => 'Veuillez préciser votre adresse ou un repère connu.',
            'address.min' => 'L\'adresse doit comporter au moins 4 caractères.',
        ]);

        $user->update([
            'phone' => trim($validated['phone']),
            'city' => $validated['city'] ?: 'Bamako',
            'neighborhood' => trim($validated['neighborhood']),
            'address' => trim($validated['address']),
        ]);

        if (\App\Services\CartService::count() > 0) {
            return redirect()->route('checkout')->with('success', 'Votre adresse de livraison a été enregistrée ! Vous pouvez finaliser votre commande.');
        }

        return redirect()->route('home')->with('success', 'Votre adresse de livraison a été enregistrée avec succès.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidation et renouvellement du token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Vous avez été déconnecté.');
    }
}
