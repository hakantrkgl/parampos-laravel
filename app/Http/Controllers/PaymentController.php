<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // Ödeme formunu gösteren metot
    public function showPaymentForm()
    {
        return view('payment');
    }

    // Ödeme işlemini gerçekleştiren metot
    public function processPayment(Request $request)
    {
        $paymentData = $request->all();

        // Ödeme verilerini hata ayıklamak için loglama
        Log::info('Payment Data: ', $paymentData);

        // Gerekli tüm anahtarların ödeme verilerinde bulunduğundan emin olun
        $requiredKeys = ['gsm', 'amount', 'installment', 'cardOwner', 'currencyCode', 'transactionId', 'ipAddress', 'orderID'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $paymentData)) {
                Log::error("Missing key in payment data: $key");
                return response()->json(['error' => 'Payment failed.'], 400);
            }
        }

        // Amount ve totalAmount değerlerini doğru formata çevirin
        $amount = str_replace(',', '.', $paymentData['amount']);
        $totalAmount = str_replace(',', '.', $paymentData['totalAmount']);

        // Sipariş verilerini veritabanına kaydet
        Order::create([
            'gsm' => $paymentData['gsm'],
            'amount' => $amount,
            'installment' => $paymentData['installment'],
            'card_owner' => $paymentData['cardOwner'],
            'currency_code' => $paymentData['currencyCode'],
            'transaction_id' => $paymentData['transactionId'],
            'ip_address' => $paymentData['ipAddress'],
            'order_id' => $paymentData['orderID'],
            'order_description' => $paymentData['orderDescription'],
            'total_amount' => $totalAmount,
            'security_type' => $paymentData['securityType']
        ]);

        return response()->json(['success' => 'Payment processed successfully.']);
    }
}
