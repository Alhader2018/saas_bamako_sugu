<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Services\OrangeMoneyService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class OrangeMoneyServiceTest extends TestCase
{
    public function test_service_throws_exception_if_credentials_missing(): void
    {
        Config::set('services.orange_money.client_id', '');
        Config::set('services.orange_money.client_secret', '');

        $this->expectException(RuntimeException::class);

        $order = new Order([
            'order_number' => 'BKO-TEST-001',
            'total' => 25000,
        ]);

        $service = new OrangeMoneyService();
        $service->createWebPayment($order);
    }

    public function test_service_creates_web_payment_successfully_with_mock(): void
    {
        Config::set('services.orange_money.client_id', 'test_client_id');
        Config::set('services.orange_money.client_secret', 'test_client_secret');
        Config::set('services.orange_money.merchant_key', 'MERCHANTKEY123');
        Config::set('services.orange_money.currency', 'OUV');
        Config::set('services.orange_money.oauth_token_url', 'https://api.orange.com/oauth/v3/token');
        Config::set('services.orange_money.webpayment_url', 'https://api.orange.com/webpayment');

        Http::fake([
            'https://api.orange.com/oauth/v3/token' => Http::response([
                'access_token' => 'mocked_access_token_123',
                'expires_in' => 3600,
            ], 200),
            'https://api.orange.com/webpayment' => Http::response([
                'status' => '201',
                'message' => 'OK',
                'payment_url' => 'https://webpayment.orange.com/pay/abc123xyz',
                'pay_token' => 'token_pay_999',
                'notif_token' => 'token_notif_888',
                'order_id' => 'BKO-TEST-001',
            ], 200),
        ]);

        $order = new Order([
            'order_number' => 'BKO-TEST-001',
            'total' => 25000,
        ]);

        $service = new OrangeMoneyService();
        $result = $service->createWebPayment($order);

        $this->assertIsArray($result);
        $this->assertEquals('https://webpayment.orange.com/pay/abc123xyz', $result['payment_url']);
        $this->assertEquals('token_pay_999', $result['pay_token']);
    }

    public function test_service_checks_transaction_status_with_mock(): void
    {
        Config::set('services.orange_money.client_id', 'test_client_id');
        Config::set('services.orange_money.client_secret', 'test_client_secret');
        Config::set('services.orange_money.merchant_key', 'MERCHANTKEY123');
        Config::set('services.orange_money.transaction_status_url', 'https://api.orange.com/transactionstatus');

        Http::fake([
            'https://api.orange.com/oauth/v3/token' => Http::response([
                'access_token' => 'mocked_access_token_123',
            ], 200),
            'https://api.orange.com/transactionstatus' => Http::response([
                'status' => 'SUCCESS',
                'txnid' => 'MP260904.1735.A00123',
                'order_id' => 'BKO-TEST-001',
            ], 200),
        ]);

        $order = new Order([
            'order_number' => 'BKO-TEST-001',
            'total' => 25000,
            'orange_money_pay_token' => 'token_pay_999',
        ]);

        $service = new OrangeMoneyService();
        $result = $service->checkTransactionStatus($order);

        $this->assertIsArray($result);
        $this->assertEquals('SUCCESS', $result['status']);
        $this->assertEquals('MP260904.1735.A00123', $result['txnid']);
    }
}
