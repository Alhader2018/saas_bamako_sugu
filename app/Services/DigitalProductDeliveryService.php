<?php

namespace App\Services;

use App\Mail\DigitalProductAccessMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DigitalProductDeliveryService
{
    /**
     * Envoie automatiquement les liens de téléchargement par email dès que la commande est payée.
     */
    public static function sendDeliveryIfPaid(Order $order): bool
    {
        if (!$order->isPaid() || !$order->hasDigitalItems()) {
            return false;
        }

        $email = trim((string) ($order->customer_email ?: $order->user?->email));

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning("Impossible d'envoyer les liens de téléchargement pour la commande {$order->order_number} : aucune adresse email valide.");
            return false;
        }

        try {
            // Charger les relations nécessaires pour le template
            $order->loadMissing('items.product.files', 'user');

            Mail::to($email)->send(new DigitalProductAccessMail($order));

            Log::info("Liens de téléchargement envoyés avec succès par email pour la commande {$order->order_number} à {$email}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Échec lors de l'envoi de l'email de téléchargement pour la commande {$order->order_number} : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Renvoie manuellement les liens de téléchargement (ex: par l'administrateur ou le support client).
     */
    public static function resend(Order $order, ?string $toEmail = null): bool
    {
        $targetEmail = $toEmail ?: ($order->customer_email ?: $order->user?->email);
        $targetEmail = trim((string) $targetEmail);

        if ($targetEmail === '' || !filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        try {
            $order->loadMissing('items.product.files', 'user');
            Mail::to($targetEmail)->send(new DigitalProductAccessMail($order));
            return true;
        } catch (\Throwable $e) {
            Log::error("Erreur renvoi manuel email téléchargement {$order->order_number} : " . $e->getMessage());
            return false;
        }
    }
}
