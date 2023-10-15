<?php

namespace App\Services;

use App\Models\ApiCall;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class BlackHawkService
{
    protected readonly string $catalogApi;
    protected readonly string $orderApi;
    protected readonly string $clientProgramId;
    protected readonly string $merchantId;
    protected readonly string $cert;
    protected readonly string $certPassword;

    protected static ?self $instance = null;

    const DUMMY_URL_PREFIX = 'Please_Replace_This_';

    public function __construct()
    {
        $this->catalogApi = config('services.blackhawk.catalog_api');
        $this->orderApi = config('services.blackhawk.order_api');
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
    public static function catalog(?string $previousReq)
    {
        $instance = static::instance();

        $result = [];

        $requestId = uniqid();
        $headers = [
            'requestId' => $requestId, // This should be a unique id from our api call log
            'merchantId' => $instance->merchantId,
            'accept' => 'application/json; charset=utf-8'
        ];

        ApiCall::create([
            'api' => 'catalog',
            'request_id' => $requestId,
            'response' => null,
            'success' => null,
            'previous_request' => $previousReq ?? null,
            'created_at' => now()
        ]);

        if ($previousReq) {
            ApiCall::where('request_id', $previousReq)->update(['allow_retry' => false]);
        }

        $promise = Http::async()->withHeaders($headers)->withOptions([
            'cert' => [$instance->cert, $instance->certPassword]
        ])
            ->get(
                "{$instance->catalogApi}/clientProgram/byKey",
                ['clientProgramId' => $instance->clientProgramId]
            )->then(
                function ($response) use (&$result) {
                    $result = [
                        'response' => $response->json(),
                        'success' => $response->ok()
                    ];
                    ApiCall::where('api', 'catalog')->orderBy('id', 'desc')->first()->update($result);
                }
            );

        $promise->wait();
        return $result;
    }

    // This is the place order endpoint for egift cards in realtime
    public static function order(Order $order, ?string $previousReq = null)
    {
        // There should be a waiting period such that the last request has a response received (not null) before retyring the api call again
        $instance = static::instance();

        $result = [];

        $requestId = uniqid();
        $headers = [
            'requestId' => $requestId, // This should be a unique id from our api call log
            'clientProgramNumber' => $instance->clientProgramId,
            'millisecondsToWait' => 15000,
            'merchantId' => $instance->merchantId,
            'SYNCHRONOUS_ONLY' => 'true',
            'Content-Type' => 'application/json'
        ];

        $refId = uniqid();
        $order->loadMissing('orderDetails.discount');
        $orderDetails = $order->orderDetails->map(function ($orderDetail) use ($refId) {
            return [
                'clientRefId' => (string) $refId,
                'quantity' => (string) $orderDetail->quantity,
                'amount' => (string) ($orderDetail->amount / 100),
                'contentProvider' => (string) $orderDetail->discount->code
            ];
        });

        $reqData = [
            'clientProgramNumber' => $instance->clientProgramId,
            'paymentType' => 'ACH_DEBIT',
            'returnCardNumberAndPIN' => 'true',
            'orderDetails' => $orderDetails,
        ];

        ApiCall::create([
            'api' => 'order',
            'request_id' => $requestId,
            'order_id' => $order->id,
            'response' => null,
            'success' => null,
            'previous_request' => $previousReq ?? null,
            'created_at' => now(),
            'request' => $reqData
        ]);

        if ($previousReq) {
            ApiCall::where('request_id', $previousReq)->update(['allow_retry' => false]);
        }

        $promise = Http::async()->withHeaders($headers)->withOptions([
            'cert' => [$instance->cert, $instance->certPassword]
        ])
            ->post(
                "{$instance->orderApi}/submitRealTimeEgiftBulk",
                $reqData
            )->then(
                function ($response) use (&$result) {
                    $result = [
                        'response' => $response->json(),
                        'success' => $response->created(), // $resposne->accepeted() or 202 we treat as failure
                    ];
                    ApiCall::where('api', 'order')->orderBy('id', 'desc')->first()->update($result);
                }
            );

        $promise->wait();
        return $result;
    }

    public static function bulkOrder(Order $order)
    {
        $instance = static::instance();

        $result = [];

        $requestId = uniqid();

        $headers = [
            'requestId' => $requestId, // This should be a unique id from our api call log
            'clientProgramNumber' => $instance->clientProgramId,
            'millisecondsToWait' => 15000,
            'merchantId' => $instance->merchantId,
            'SYNCHRONOUS_ONLY' => 'true',
            'Content-Type' => 'application/json'
        ];

        $refId = uniqid();
        $order->loadMissing('orderDetails.discount');
        $orderDetails = $order->orderDetails->map(function ($orderDetail) use ($refId) {
            return [
                'clientRefId' => (string) $refId,
                'quantity' => (string) $orderDetail->quantity,
                'amount' => (string) ($orderDetail->amount / 100),
                'contentProvider' => (string) $orderDetail->discount->code
            ];
        });

        $reqData = [
            'clientProgramNumber' => $instance->clientProgramId,
            'paymentType' => 'ACH_DEBIT',
            'returnCardNumberAndPIN' => 'true',
            'orderDetails' => $orderDetails,
        ];

        ApiCall::create([
            'api' => 'order',
            'request_id' => $requestId,
            'order_id' => $order->id,
            'response' => null,
            'success' => null,
            'created_at' => now(),
            'request' => $reqData
        ]);

        $promise = Http::async()->withHeaders($headers)->withOptions([
            'cert' => [$instance->cert, $instance->certPassword]
        ])
            ->post(
                "{$instance->orderApi}/submitRealTimeEgiftBulk",
                $reqData
            )->then(
                function ($response) use (&$result) {
                    $result = [
                        'response' => $response->json(),
                        'success' => $response->created(), // $resposne->accepeted() or 202 we treat as failure
                    ];
                    ApiCall::where('api', 'order')->orderBy('id', 'desc')->first()->update($result);
                }
            );

        $promise->wait();
        return;
    }
}
