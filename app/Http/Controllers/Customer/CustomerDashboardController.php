<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\CustomerNotification;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerDashboardController extends Controller
{
    private function syncGuestOrders(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // Lier automatiquement toute commande passée antérieurement avec son email ou téléphone
        Order::whereNull('user_id')
            ->where(function ($query) use ($user) {
                if (!empty($user->email)) {
                    $query->where('customer_email', $user->email);
                }
                if (!empty($user->phone)) {
                    $query->orWhere('customer_phone', $user->phone);
                }
            })
            ->update(['user_id' => $user->id]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $this->syncGuestOrders();

        $ordersCount = $user->orders()->count();
        $inProgressCount = $user->orders()->whereIn('status', ['pending', 'confirmed', 'in_preparation', 'processing', 'in_delivery'])->count();
        $favoritesCount = $user->favorites()->count();

        // Commande active principale (la plus récente non terminée)
        $activeOrder = $user->orders()
            ->with(['items.product'])
            ->whereIn('status', ['pending', 'confirmed', 'in_preparation', 'processing', 'in_delivery'])
            ->latest()
            ->first();

        // Commandes récentes (3 à 5 commandes)
        $recentOrders = $user->orders()
            ->with(['items'])
            ->latest()
            ->take(5)
            ->get();

        return view('account.dashboard', compact(
            'user',
            'ordersCount',
            'inProgressCount',
            'favoritesCount',
            'activeOrder',
            'recentOrders'
        ));
    }

    public function orders(Request $request)
    {
        $user = Auth::user();
        $this->syncGuestOrders();

        $query = $user->orders()->with('items')->latest();

        // Recherche par numéro de commande
        if ($search = $request->input('search')) {
            $query->where('order_number', 'like', "%{$search}%");
        }

        // Filtre par statut
        $status = $request->input('status', 'all');
        if ($status === 'in_progress') {
            $query->whereIn('status', ['pending', 'confirmed', 'in_preparation', 'processing', 'in_delivery']);
        } elseif ($status === 'delivered') {
            $query->where('status', 'delivered');
        } elseif ($status === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        // Filtre par période
        $period = $request->input('period', 'all');
        if ($period === '30_days') {
            $query->where('created_at', '>=', now()->subDays(30));
        } elseif ($period === '3_months') {
            $query->where('created_at', '>=', now()->subMonths(3));
        } elseif ($period === 'year') {
            $query->where('created_at', '>=', now()->subYear());
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('account.orders.index', compact('user', 'orders', 'status', 'period', 'search'));
    }

    public function showOrder(Order $order)
    {
        $user = Auth::user();

        // Sécurité absolue : contrôle d'appartenance côté serveur
        abort_if($order->user_id !== $user->id, 403, 'Accès non autorisé à cette commande.');

        $order->load(['items.product.files']);

        return view('account.orders.show', compact('user', 'order'));
    }

    public function downloads()
    {
        $user = Auth::user();
        $this->syncGuestOrders();

        // Récupérer toutes les commandes payées de l'utilisateur contenant des articles numériques
        $orders = $user->orders()
            ->where('payment_status', 'paid')
            ->whereHas('items', function ($q) {
                $q->where('product_type', 'digital');
            })
            ->with(['items' => function ($q) {
                $q->where('product_type', 'digital')->with('product.files');
            }])
            ->latest()
            ->get();

        // Téléchargements déjà effectués pour calculer le restant
        $downloadsCount = \App\Models\DigitalProductDownload::where('user_id', $user->id)
            ->selectRaw('product_file_id, count(*) as count')
            ->groupBy('product_file_id')
            ->pluck('count', 'product_file_id')
            ->toArray();

        return view('account.downloads', compact('user', 'orders', 'downloadsCount'));
    }

    public function cancelOrder(Order $order)
    {
        $user = Auth::user();
        abort_if($order->user_id !== $user->id, 403, 'Accès non autorisé à cette commande.');

        if (!$order->isCancellable()) {
            return back()->with('error', 'Cette commande ne peut plus être annulée car elle est déjà en cours de préparation ou expédiée.');
        }

        // Réattribution du stock
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        CustomerNotification::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'title' => "Commande #{$order->order_number} annulée",
            'message' => "Votre commande a été annulée avec succès. Aucun montant n'a été débité.",
            'type' => 'cancelled',
        ]);

        return back()->with('success', "La commande #{$order->order_number} a bien été annulée.");
    }

    public function reorder(Order $order)
    {
        $user = Auth::user();
        abort_if($order->user_id !== $user->id, 403, 'Accès non autorisé à cette commande.');

        $addedCount = 0;
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product && $product->stock > 0) {
                CartService::add($product->id, $item->quantity);
                $addedCount++;
            }
        }

        if ($addedCount === 0) {
            return back()->with('error', 'Les articles de cette commande ne sont actuellement plus en stock.');
        }

        return redirect()->route('checkout')->with('success', "{$addedCount} produit(s) ont été réajoutés à votre panier !");
    }

    public function favorites()
    {
        $user = Auth::user();
        $favorites = $user->favoriteProducts()->latest('favorites.created_at')->paginate(12);

        return view('account.favorites.index', compact('user', 'favorites'));
    }

    public function toggleFavorite(Request $request, Product $product)
    {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $favorited = false;
            $message = "\"{$product->name}\" a été retiré de vos favoris.";
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $favorited = true;
            $message = "\"{$product->name}\" a été ajouté à vos favoris.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'count' => $user->favorites()->count(),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->latest('is_default')->latest()->get();

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

        return view('account.addresses.index', compact('user', 'addresses', 'neighborhoods'));
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|min:8|max:25',
            'neighborhood' => 'required|string|max:100',
            'commune' => 'nullable|string|max:50',
            'address' => 'required|string|min:4|max:500',
            'delivery_notes' => 'nullable|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        $isDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        CustomerAddress::create([
            'user_id' => $user->id,
            'label' => trim($validated['label']),
            'recipient_name' => trim($validated['recipient_name']),
            'phone' => trim($validated['phone']),
            'city' => 'Bamako',
            'commune' => $validated['commune'] ? trim($validated['commune']) : null,
            'neighborhood' => trim($validated['neighborhood']),
            'address' => trim($validated['address']),
            'delivery_notes' => $validated['delivery_notes'] ? trim($validated['delivery_notes']) : null,
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'Votre adresse de livraison a été enregistrée avec succès.');
    }

    public function setDefaultAddress(CustomerAddress $address)
    {
        $user = Auth::user();
        abort_if($address->user_id !== $user->id, 403);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', "L'adresse \"{$address->label}\" est désormais votre adresse principale.");
    }

    public function destroyAddress(CustomerAddress $address)
    {
        $user = Auth::user();
        abort_if($address->user_id !== $user->id, 403);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault && $firstRemaining = $user->addresses()->first()) {
            $firstRemaining->update(['is_default' => true]);
        }

        return back()->with('success', 'Adresse supprimée avec succès.');
    }

    public function payments()
    {
        $user = Auth::user();
        $this->syncGuestOrders();

        $orders = $user->orders()->latest()->paginate(15);

        return view('account.payments.index', compact('user', 'orders'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->customerNotifications()->paginate(15);

        // Marquer comme lues
        $user->customerNotifications()->whereNull('read_at')->update(['read_at' => now()]);

        return view('account.notifications.index', compact('user', 'notifications'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('account.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'phone' => 'required|string|min:8|max:25',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'neighborhood' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => trim($validated['name']),
            'phone' => trim($validated['phone']),
            'email' => strtolower(trim($validated['email'])),
            'neighborhood' => $validated['neighborhood'] ? trim($validated['neighborhood']) : $user->neighborhood,
            'address' => $validated['address'] ? trim($validated['address']) : $user->address,
        ]);

        return back()->with('success', 'Vos informations personnelles ont été mises à jour.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required_with:password|current_password',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le nouveau mot de passe doit comporter au moins 8 caractères.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}
