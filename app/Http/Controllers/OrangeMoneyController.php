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
            // Tenter de vérifier le statut auprès de l'API Orange Money
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
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Vérification Orange Money en retour: ' . $e->getMessage());
            }

            return view('store.orange-return', [
                'order' => $order,
                'status' => $order->payment_status,
            ]);
        }

        return redirect()->route('home')->with('info', 'Commande terminée.');
    }

    public function cancel(Request $request)
    {
        $orderId = $request->get('order_id') ?: $request->get('orderId');

        if ($orderId) {
            $order = Order::where('order_number', $orderId)
                ->orWhere('orange_money_order_id', $orderId)
                ->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'cancelled',
                ]);
            }
        }

        return redirect()->route('checkout')->with('error', 'Le paiement Orange Money a été annulé. Vous pouvez réessayer ou choisir le paiement en espèces.');
    }

    public function notif(Request $request)
    {
        Log::info('IPN Orange Money reçu', $request->all());

        $orderId = $request->input('order_id');
        $status = strtolower($request->input('status', ''));
        $txnid = $request->input('txnid');

        if ($orderId) {
            $order = Order::where('order_number', $orderId)
                ->orWhere('orange_money_order_id', $orderId)
                ->first();

            if ($order) {
                if (in_array($status, ['success', 'successful', 'completed'])) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'orange_money_transaction_id' => $txnid ?: $order->orange_money_transaction_id,
                    ]);
                } elseif (in_array($status, ['failed', 'cancelled', 'expired'])) {
                    $order->update([
                        'payment_status' => 'failed',
                    ]);
                }
            }
        }

        return response()->json(['status' => 'acknowledged'], 200);
    }
}
