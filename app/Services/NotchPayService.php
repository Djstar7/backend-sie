<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class NotchPayService
{
    protected $client;
    protected $secretKey;
    protected $publicKey;
    protected $dataSend;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NOTCHPAY_BASE_URL', 'https://api.notchpay.co/'),
            'timeout'  => 30.0,
        ]);

        $this->secretKey = env('NOTCHPAY_SECRET_KEY');
        $this->publicKey = env('NOTCHPAY_PUBLIC_KEY');
    }

    /**
     * Initialise un paiement
     */
    public function initializePayment(array $data): array
    {
        $this->dataSend = $data;
        return $this->request('POST', 'payments', $data);
    }

    /**
     * Vérifie le statut d’un paiement
     */
    public function verifyPayment(string $reference): array
    {
        return $this->request('GET', "transaction/verify/{$reference}");
    }

    /**
     * Fonction générique pour les requêtes NotchPay
     */
   protected function request(string $method, string $endpoint, array $data = []): array
{
    try {
        $options = [
            'headers' => [
                'Authorization' => $this->publicKey,
                'Accept'        => 'application/json',
            ],
            'verify' => true, // vérifie le certificat SSL
            'curl' => [
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2, // TLS 1.2
            ],
            'http_errors' => false, // pour capturer les erreurs HTTP
        ];

        if (!empty($data)) {
            $options['json'] = $data;
        }

        $response = $this->client->request($method, $endpoint, $options);

        return json_decode($response->getBody()->getContents(), true);
    } catch (RequestException $e) {
        Log::error('NotchPay API Error: '. $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return [
            'success' => false,
            'message' => 'Erreur lors de la communication avec NotchPay',
            'error'   => $e->getMessage(),
        ];
    }
}

}