<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BlackHawkService
{
    protected readonly string $api;
    protected readonly string $clientProgramId;
    protected readonly string $merchantId;
    protected readonly string $cert;
    protected readonly string $certPassword;

    protected static ?self $instance = null;

    public function __construct()
    {
        $this->api = config('services.blackhawk.api');
        $this->clientProgramId = config('services.blackhawk.client_program_id');
        $this->merchantId = config('services.blackhawk.merchant_id');
        $this->cert = config('services.blackhawk.cert');
        $this->certPassword = config('services.blackhawk.cert_password');
    }

    public static function instance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    // This is the catalog endpoint for egift cards
    public static function api()
    {
        // There should be a waiting period of 1 minute before retrying
        $instance = static::instance();

        $headers = [
            'requestId' => uniqid(), // This should be a unique id from our api call log
            'merchantId' => $instance->merchantId,
            'accept' => 'application/json; charset=utf-8'
        ];

        $response = Http::withHeaders($headers)->withOptions([
            'cert' => [$instance->cert, $instance->certPassword]
        ])
            ->get(
                "{$instance->api}/clientProgram/byKey",
                ['clientProgramId' => $instance->clientProgramId]
            );

        return [
            'status' => $response->ok(),
            'data' => $response->json()
        ];
    }
}
