<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use SoapClient;
use Exception;
use App\Models\Payment; // Payment model
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function showPaymentForm()
    {
        return view('payment');
    }

    // Ödeme formunu gösteren metot
    public function showPayment3DForm()
    {
        return view('payment3d');
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

    public function process3DPayment(Request $request)
    {
        $clientCode = env('PARAMPOS_CLIENT_CODE');
        $clientUsername = env('PARAMPOS_CLIENT_USERNAME');
        $clientPassword = env('PARAMPOS_CLIENT_PASSWORD');
        $guid = env('PARAMPOS_GUID');
        $wsdlUrl = env('PARAMPOS_WSDL_URL');

        $amount = floatval(str_replace(',', '.', $request->input('amount')));
        $totalAmount = floatval(str_replace(',', '.', $request->input('totalAmount')));

        $amountFormatted = number_format($amount, 2, ',', '');
        $totalAmountFormatted = number_format($totalAmount, 2, ',', '');

        $data = [
            'G' => [
                'CLIENT_CODE' => $clientCode,
                'CLIENT_USERNAME' => $clientUsername,
                'CLIENT_PASSWORD' => $clientPassword,
            ],
            'GUID' => $guid,
            'KK_Sahibi' => $request->input('cardOwner'),
            'KK_No' => $request->input('cardNumber'),
            'KK_SK_Ay' => $request->input('cardExpMonth'),
            'KK_SK_Yil' => $request->input('cardExpYear'),
            'KK_CVC' => $request->input('cardCvc'),
            'KK_Sahibi_GSM' => $request->input('gsm'),
            'Hata_URL' => route('payment.callback'),
            'Basarili_URL' => route('payment.callback'),
            'Ref_URL' => route('payment.callback'),
            'Siparis_ID' => $request->input('orderID'),
            'Siparis_Aciklama' => $request->input('orderDescription'),
            'Taksit' => $request->input('installment'),
            'Islem_Tutar' => $amountFormatted,
            'Toplam_Tutar' => $totalAmountFormatted,
            'Islem_Hash' => '',
            'Islem_Guvenlik_Tip' => $request->input('securityType'),
            'Islem_ID' => $request->input('transactionId'),
            'IPAdr' => $request->input('ipAddress'),
            'Doviz_Kodu' => $request->input('currencyCode'),
            'Data1' => '',
            'Data2' => '',
            'Data3' => '',
            'Data4' => '',
            'Data5' => '',
            'Data6' => '',
            'Data7' => '',
            'Data8' => '',
            'Data9' => '',
            'Data10' => ''
        ];

        $islemGuvenlikStr = $clientCode . $guid . $data['Taksit'] . $data['Islem_Tutar'] . $data['Toplam_Tutar'] . $data['Siparis_ID'] . $data['Hata_URL'] . $data['Basarili_URL'];
        $data['Islem_Hash'] = $this->callSHA2B64($wsdlUrl, $islemGuvenlikStr);

        if ($data['Islem_Hash'] === false) {
            return redirect()->back()->with('error', 'SHA2B64 hash calculation failed.');
        }

        try {
            $client = new SoapClient($wsdlUrl, [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ])
            ]);

            $response = $client->Pos_Odeme($data);
            $responseObj = $response->Pos_OdemeResult;

            Log::info('SOAP Request: ' . $client->__getLastRequest());
            Log::info('SOAP Response: ' . $client->__getLastResponse());

            if ($responseObj->Sonuc > 0 && !empty($responseObj->UCD_URL)) {
                // 3D Secure URL'sine yönlendirme
                return redirect()->away($responseObj->UCD_URL);
            } else {
                Log::error('İşlem Başarısız: ' . $responseObj->Sonuc_Str);
                return redirect()->back()->with('error', 'İşlem Başarısız: ' . $responseObj->Sonuc_Str);
            }
        } catch (Exception $e) {
            Log::error('SOAP Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }



    public function paymentCallback(Request $request)
    {
        Log::info('Payment Callback Received');
        Log::info('Request Data:', $request->all());

        if ($request->input('TURKPOS_RETVAL_Sonuc') === '1') {
            Order::create([
                'card_owner' => $request->input('KK_Sahibi'),
                'gsm' => $request->input('KK_Sahibi_GSM'),
                'amount' => $request->input('Islem_Tutar'),
                'order_id' => $request->input('Siparis_ID'),
                'order_description' => $request->input('Siparis_Aciklama'),
                'installment' => $request->input('Taksit'),
                'total_amount' => $request->input('Toplam_Tutar'),
                'security_type' => $request->input('Islem_Guvenlik_Tip'),
                'transaction_id' => $request->input('Islem_ID'),
                'ip_address' => $request->input('IPAdr'),
                'currency_code' => $request->input('Doviz_Kodu'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return view('payment-success');
        } else {
            return view('payment-failed')->with('error', $request->input('TURKPOS_RETVAL_Sonuc_Str'));
        }
    }



    private function callSHA2B64($wsdlUrl, $data)
    {
        try {
            $client = new SoapClient($wsdlUrl, [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ])
            ]);

            $params = ['Data' => $data];
            $response = $client->SHA2B64($params);

            return $response->SHA2B64Result;
        } catch (Exception $e) {
            return false;
        }
    }
}
