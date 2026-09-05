<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OrangeMoneyService
{
    public function createWebPayment(Order $order): array
    {
        // En cas de nouvelle tentative sur la même commande, générer un identifiant d'essai unique
        $attemptId = $order->order_number;
        if (!empty($order->orange_money_pay_token) || !empty($order->orange_money_order_id)) {
            $attemptId = substr($order->order_number, 0, 24) . '-' . strtoupper(substr(uniqid(), -4));
        }

        $orderId = substr((string) $attemptId, 0, 30);
        $amount = (int) round((float) $order->grand_total);
        $reference = substr('REF-' . $order->order_number, 0, 30);

        $payload = [
            'merchant_key' => $this->getMerchantKey(),
            'currency' => config('services.orange_money.currency', 'OUV'),
            'order_id' => $orderId,
            'amount' => $amount,
            'return_url' => route('checkout.orange.return', ['order_id' => $orderId], true),
            'cancel_url' => route('checkout.orange.cancel', ['order_id' => $orderId], true),
            'notif_url' => route('checkout.orange.notif', [], true),
            'lang' => 'fr',
            'reference' => $reference,
        ];

        $response = Http::withToken($this->getAccessToken())
            ->withOptions([
                'verify' => $this->resolveVerifyOption(),
            ])
            ->acceptJson()
            ->timeout(30)
            ->post(config('services.orange_money.webpayment_url'), $payload);

        if ($response->failed()) {
            throw new RuntimeException('Erreur API Orange Money: ' . $response->body());
        }

        $data = $response->json();
        if (!is_array($data) || empty($data['payment_url'])) {
            throw new RuntimeException('Réponse Orange Money invalide: payment_url absent.');
        }

        // Sauvegarder les tokens retournés sur la commande
        $order->update([
            'orange_money_order_id' => $data['order_id'] ?? $orderId,
            'orange_money_pay_token' => $data['pay_token'] ?? null,
            'orange_money_notif_token' => $data['notif_token'] ?? null,
        ]);

        return $data;
    }

    public function checkTransactionStatus(Order $order): array
    {
        $payload = [
            'order_id' => $order->orange_money_order_id ?: $order->order_number,
            'amount' => (int) round((float) $order->grand_total),
        ];

        if (!empty($order->orange_money_pay_token)) {
            $payload['pay_token'] = $order->orange_money_pay_token;
        }

        $response = Http::withToken($this->getAccessToken())
            ->withOptions([
                'verify' => $this->resolveVerifyOption(),
            ])
            ->acceptJson()
            ->timeout(30)
            ->post(config('services.orange_money.transaction_status_url'), $payload);

        if ($response->failed()) {
            throw new RuntimeException('Erreur statut transaction Orange Money: ' . $response->body());
        }

        $data = $response->json();
        if (!is_array($data)) {
            throw new RuntimeException('Réponse transactionstatus invalide.');
        }

        return $data;
    }

    private function getAccessToken(): string
    {
        $clientId = $this->cleanCredential((string) config('services.orange_money.client_id'));
        $clientSecret = $this->cleanCredential((string) config('services.orange_money.client_secret'));

        if ($clientId === '' || $clientSecret === '') {
            throw new RuntimeException('Configuration Orange Money incomplète: client_id/client_secret manquants.');
        }

        $response = Http::withBasicAuth($clientId, $clientSecret)
            ->asForm()
            ->withOptions([
                'verify' => $this->resolveVerifyOption(),
            ])
            ->timeout(30)
            ->post(config('services.orange_money.oauth_token_url'), [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Impossible de récupérer le token Orange Money: ' . $response->body());
        }

        $accessToken = (string) $response->json('access_token');
        if ($accessToken === '') {
            throw new RuntimeException('Réponse OAuth Orange Money invalide: access_token absent.');
        }

        return $accessToken;
    }

    private function cleanCredential(string $value): string
    {
        $value = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $value);
        return trim((string) $value);
    }

    private function getMerchantKey(): string
    {
        $merchantKey = trim((string) config('services.orange_money.merchant_key'));

        if ($merchantKey === '') {
            throw new RuntimeException('Configuration Orange Money incomplète: merchant_key manquant.');
        }

        if (preg_match('/\s/', $merchantKey) === 1 || preg_match('/^[A-Za-z0-9]{6,32}$/', $merchantKey) !== 1) {
            throw new RuntimeException('merchant_key Orange Money invalide.');
        }

        return $merchantKey;
    }

    private function resolveVerifyOption(): bool|string
    {
        $sslVerify = (bool) config('services.orange_money.ssl_verify', true);
        $caCertPath = (string) config('services.orange_money.ca_cert_path', '');

        if (!$sslVerify) {
            return false;
        }

        if ($caCertPath !== '' && file_exists($caCertPath)) {
            return $caCertPath;
        }

        return true;
    }
}
