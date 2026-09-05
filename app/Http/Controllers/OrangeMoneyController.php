<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrangeMoneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrangeMoneyController extends Controller
{
    public function __construct(
        protected OrangeMoneyService $orangeMoneyService
    ) {}

    public function return(Request $request)
    {
        $orderId = $request->get('order_id') ?: $request->get('orderId');
        $order = null;

        if ($orderId) {
            $order = Order::where('order_number', $orderId)
                ->orWhere('orange_money_order_id', $orderId)
                ->first();
        }

        if ($order) {
            // Protection IDOR : vérifier que la commande appartient à l'acheteur ou à la session en cours
            $isOwner = auth()->check() && (int) auth()->id() === (int) $order->user_id;
            $hasSessionAccess = session()->get('accessible_order_tokens.' . $order->order_number) === $order->tracking_token;
            $isStaff = auth()->check() && auth()->user()->isStaff();

            if (!$isOwner && !$hasSessionAccess && !$isStaff) {
                abort(403, 'Vous n\'avez pas l\'autorisation de consulter cette commande.');
            }

            // Tenter de vérifier le statut auprès de l'API Orange Money si pas encore validé
            if ($order->payment_status !== 'paid') {
                try {
                    if (config('services.orange_money.client_id')) {
                        $statusData = $this->orangeMoneyService->checkTransactionStatus($order);
                        $status = strtolower($statusData['status'] ?? '');

                        if (in_array($status, ['success', 'successful', 'completed'])) {
                            $order->update([
                                'payment_status' => 'paid',
                                'status' => 'confirmed',
                                'orange_money_transaction_id' => $statusData['txnid'] ?? $order->orange_money_transaction_id,
                            ]);
                        } elseif (in_array($status, ['failed', 'expired', 'declined', 'cancelled'])) {
                            $order->update([
                                'payment_status' => 'failed',
                                'status' => 'cancelled',
                            ]);
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('Vérification Orange Money en retour: ' . $e->getMessage());
                }
            }

            $order = $order->fresh();

            return view('store.orange-return', [
                'order' => $order,
                'status' => $order->payment_status,
            ]);
        }

        return redirect()->route('home')->with('info', 'Commande introuvable ou terminée.');
    }

    public function cancel(Request $request)
    {
        $orderId = $request->get('order_id') ?: $request->get('orderId');

        if ($orderId) {
            $order = Order::where('order_number', $orderId)
                ->orWhere('orange_money_order_id', $orderId)
                ->first();

            if ($order) {
                // Protection IDOR
                $isOwner = auth()->check() && (int) auth()->id() === (int) $order->user_id;
                $hasSessionAccess = session()->get('accessible_order_tokens.' . $order->order_number) === $order->tracking_token;
                
                if ($isOwner || $hasSessionAccess || (auth()->check() && auth()->user()->isStaff())) {
                    if ($order->payment_status !== 'paid') {
                        $order->update([
                            'payment_status' => 'cancelled',
                            'status' => 'cancelled',
                        ]);
                    }
                }
            }
        }

        return redirect()->route('checkout')->with('error', 'Le paiement Orange Money a été annulé. Vous pouvez réessayer ou choisir le paiement en espèces.');
    }

    public function notif(Request $request)
    {
        // Journalisation assainie (sanitizing logs)
        Log::info('IPN Orange Money reçu', [
            'status' => $request->input('status'),
            'txnid' => $request->input('txnid'),
            'order_id' => $request->input('order_id'),
            'ip' => $request->ip(),
        ]);

        $notifToken = $request->input('notif_token');
        $status = strtoupper(trim((string) $request->input('status', '')));
        $txnid = $request->input('txnid');
        $orderId = $request->input('order_id');

        $order = null;

        // Validation prioritaire par notif_token (recommandation guide officiel Orange)
        if ($notifToken) {
            $order = Order::where('orange_money_notif_token', $notifToken)->first();
        }

        // Fallback par order_id si non trouvé par token
        if (!$order && $orderId) {
            $order = Order::where('order_number', $orderId)
                ->orWhere('orange_money_order_id', $orderId)
                ->first();
        }

        if ($order) {
            // Idempotence : Si déjà marqué payé, acquitter immédiatement sans effets secondaires
            if ($order->payment_status === 'paid' && $status === 'SUCCESS') {
                return response()->json(['status' => 'already_processed', 'order_id' => $order->order_number], 200);
            }

            if ($status === 'SUCCESS') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'orange_money_transaction_id' => $txnid ?: $order->orange_money_transaction_id,
                ]);
            } elseif (in_array($status, ['FAILED', 'EXPIRED'])) {
                $order->update([
                    'payment_status' => 'failed',
                ]);
            }

            return response()->json(['status' => 'acknowledged', 'order_id' => $order->order_number], 200);
        }

        Log::warning('IPN Orange Money: Aucune commande correspondante trouvée.', [
            'order_id' => $orderId,
            'ip' => $request->ip(),
        ]);

        return response()->json(['status' => 'order_not_found'], 404);
    }
}
