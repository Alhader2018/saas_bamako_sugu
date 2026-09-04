<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $orangeMoneyConfig = [
            'client_id' => config('services.orange_money.client_id'),
            'merchant_key' => config('services.orange_money.merchant_key'),
            'currency' => config('services.orange_money.currency', 'OUV'),
            'mode' => config('services.orange_money.currency') === 'OUV' ? 'Sandbox DEV (Test)' : 'Production Réelle',
            'token_url' => config('services.orange_money.oauth_token_url'),
            'webpayment_url' => config('services.orange_money.webpayment_url'),
        ];

        $storeInfo = [
            'name' => 'BKO SU — Bamako Supermarché',
            'phone' => '+223 70 00 00 00',
            'email' => 'contact@bamakosugu.com',
            'address' => 'ACI 2000, Bamako, Mali',
            'currency' => 'FCFA (XOF)',
            'standard_delivery_fee' => 1500,
            'free_delivery_threshold' => 25000,
        ];

        return view('admin.settings.index', compact('orangeMoneyConfig', 'storeInfo'));
    }
}
