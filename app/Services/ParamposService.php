<?php

namespace App\Services;

use SoapClient;
use Exception;
use Illuminate\Support\Facades\Log; // Log sınıfını ekleyin

class ParamposService
{
    protected $wsdlUrl;
    protected $clientCode;
    protected $clientUsername;
    protected $clientPassword;
    protected $guid;
    protected $callbackUrl;

    public function __construct()
    {
        $this->wsdlUrl = config('parampos.wsdl_url');
        $this->clientCode = config('parampos.client_code');
        $this->clientUsername = config('parampos.client_username');
        $this->clientPassword = config('parampos.client_password');
        $this->guid = config('parampos.guid');
        $this->callbackUrl = config('parampos.callback_url');
    }

    public function callSHA2B64($data)
    {
        try {
            $client = new SoapClient($this->wsdlUrl, [
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
            // Hata durumunda loglama
            Log::error('Error in SHA2B64: ' . $e->getMessage());
            return false;
        }
    }

    public function makePayment($paymentData)
    {
        $requiredKeys = ['installment', 'amount', 'totalAmount', 'orderID', 'cardOwner', 'cardNumber', 'cardExpMonth', 'cardExpYear', 'cardCvc', 'gsm', 'orderDescription', 'securityType', 'transactionId', 'ipAddress', 'currencyCode'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $paymentData)) {
                Log::error("Missing key in payment data: $key");
                return false;
            }
        }

        $islemGuvenlikStr = $this->clientCode . $this->guid . $paymentData['installment'] . $paymentData['amount'] . $paymentData['totalAmount'] . $paymentData['orderID'] . $this->callbackUrl . $this->callbackUrl;
        $islemHash = $this->callSHA2B64($islemGuvenlikStr);

        if ($islemHash === false) {
            return false;
        }

        $data = [
            'G' => [
                'CLIENT_CODE' => $this->clientCode,
                'CLIENT_USERNAME' => $this->clientUsername,
                'CLIENT_PASSWORD' => $this->clientPassword,
            ],
            'GUID' => $this->guid,
            'KK_Sahibi' => $paymentData['cardOwner'],
            'KK_No' => $paymentData['cardNumber'],
            'KK_SK_Ay' => $paymentData['cardExpMonth'],
            'KK_SK_Yil' => $paymentData['cardExpYear'],
            'KK_CVC' => $paymentData['cardCvc'],
            'KK_Sahibi_GSM' => $paymentData['gsm'],
            'Hata_URL' => $this->callbackUrl,
            'Basarili_URL' => $this->callbackUrl,
            'Siparis_ID' => $paymentData['orderID'],
            'Siparis_Aciklama' => $paymentData['orderDescription'],
            'Taksit' => $paymentData['installment'],
            'Islem_Tutar' => $paymentData['amount'],
            'Toplam_Tutar' => $paymentData['totalAmount'],
            'Islem_Hash' => $islemHash,
            'Islem_Guvenlik_Tip' => $paymentData['securityType'],
            'Islem_ID' => $paymentData['transactionId'],
            'IPAdr' => $paymentData['ipAddress'],
            'Ref_URL' => $this->callbackUrl,
            'Doviz_Kodu' => $paymentData['currencyCode'],
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

        try {
            $client = new SoapClient($this->wsdlUrl, [
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

            return $response;
        } catch (Exception $e) {
            Log::error('Error in makePayment: ' . $e->getMessage());
            return false;
        }
    }
}
