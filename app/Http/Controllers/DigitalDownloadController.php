<?php

namespace App\Http\Controllers;

use App\Models\DigitalProductDownload;
use App\Models\Order;
use App\Models\ProductFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DigitalDownloadController extends Controller
{
    public function download(Request $request, string $orderNumber, int $fileId)
    {
        $order = Order::with(['items.product'])->where('order_number', $orderNumber)->firstOrFail();

        // 1. Contrôle d'autorisation strict (Anti-IDOR)
        $isOwner = Auth::check() && ((int) Auth::id() === (int) $order->user_id);
        $isStaff = Auth::check() && method_exists(Auth::user(), 'isStaff') && Auth::user()->isStaff();
        $hasSessionAccess = session()->get('accessible_order_tokens.' . $order->order_number) === $order->tracking_token;
        $hasValidSignature = $request->hasValidSignature();

        if (!$isOwner && !$isStaff && !$hasSessionAccess && !$hasValidSignature) {
            abort(403, 'Accès non autorisé. Veuillez vous connecter avec le compte acheteur ou utiliser le lien fourni dans votre confirmation.');
        }

        // 2. Contrôle de paiement réel
        if (!$order->isPaid() && !$isStaff) {
            abort(402, 'Cette commande n\'est pas encore validée comme payée. Les téléchargements sont réservés aux commandes confirmées.');
        }

        // 3. Fichier demandé
        $file = ProductFile::with('product')->findOrFail($fileId);

        // 4. Vérifier que le fichier appartient bien à un article acheté dans cette commande
        $orderItem = $order->items->first(function ($item) use ($file) {
            return (int) $item->product_id === (int) $file->product_id;
        });

        if (!$orderItem) {
            abort(404, 'Ce fichier ne correspond à aucun produit de cette commande.');
        }

        $product = $file->product;

        // 5. Vérification de la limite de téléchargements
        if ($product && $product->download_limit && !$isStaff) {
            $downloadsCount = DigitalProductDownload::where('order_id', $order->id)
                ->where('product_file_id', $file->id)
                ->count();

            if ($downloadsCount >= (int) $product->download_limit) {
                abort(403, "La limite maximale de téléchargements ({$product->download_limit}) a été atteinte pour ce fichier.");
            }
        }

        // 6. Vérification de la date d'expiration
        if ($product && $product->download_expiry_days && !$isStaff) {
            $expiresAt = $order->created_at->addDays((int) $product->download_expiry_days);
            if (now()->isAfter($expiresAt)) {
                abort(403, 'La période autorisée pour télécharger ce produit numérique a expiré.');
            }
        }

        // 7. Vérification de l'existence physique du fichier
        if (!Storage::disk('local')->exists($file->file_path)) {
            Log::error("Fichier numérique introuvable sur le disque : {$file->file_path} pour le fichier #{$file->id}");
            abort(404, 'Le fichier demandé est momentanément indisponible.');
        }

        // 8. Journalisation de sécurité du téléchargement
        try {
            DigitalProductDownload::create([
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'product_file_id' => $file->id,
                'user_id' => Auth::id() ?: $order->user_id,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'downloaded_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Erreur enregistrement log téléchargement : ' . $e->getMessage());
        }

        // 9. Téléchargement sécurisé en streaming privé
        return Storage::disk('local')->download($file->file_path, $file->file_name);
    }
}
