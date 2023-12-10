<?php

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
use Aws\Sts\StsClient;

$blackhawk_cert_pw = null;
$blackhawk_cert_url = null;
if (env("APP_ENV") === "production") {
    $blackhawk_cert_url = "/home/ec2-user/certificates/stag.p12";
    $stsClient = new StsClient([
        'version' => 'latest',
        'region' => "us-east-2"
    ]);

    try {
        // Assume the IAM role
        $result = $stsClient->assumeRole([
            'RoleArn' => "arn:aws:iam::891985934622:role/RoleToRetrieveSecretAtRuntime",
            'RoleSessionName' => 'session-' . time() // You can customize the session name
        ]);

        $credentials = $result['Credentials'];

        // Create SecretsManagerClient with the assumed role credentials
        $secretsManagerClient = new SecretsManagerClient([
            'version' => 'latest',
            'region' => "us-east-2",
            'credentials' => [
                'key' => $credentials['AccessKeyId'],
                'secret' => $credentials['SecretAccessKey'],
                'token' => $credentials['SessionToken']
            ]
        ]);

        // Retrieve the secret
        $response = $secretsManagerClient->getSecretValue([
            'SecretId' => 'BLACKHAWKProd',
            'VersionStage' => 'AWSCURRENT'
        ]);

        if (isset($response['SecretString'])) {
            $blackhawk_cert_pw = $response['SecretString'];
        } else {
            $blackhawk_cert_pw = base64_decode($response['SecretBinary']);
        }
    } catch (AwsException $e) {
        // Enhanced error logging
        error_log('AWS SDK Exception caught: ' . $e->getMessage());
        error_log('Error Type: ' . $e->getAwsErrorType());
        error_log('Error Code: ' . $e->getAwsErrorCode());
        error_log('Request ID: ' . $e->getAwsRequestId());
        error_log('HTTP Status Code: ' . $e->getStatusCode());
        error_log('Exception Trace: ' . $e->getTraceAsString());

    }
} else {
    $blackhawk_cert_pw = "BH3F2FDP7J4ZXJV3PB1CFM1M4C";
    $blackhawk_cert_url = public_path("key/stag.p12");
}


return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'cardknox' => [
        'ifields' => [
            'key' => env('CARDKNOX_IFIELDS_KEY'),
            'version' => '2.15.2302.0801',
        ],
        'transaction_key' => env('CARDKNOX_TRANSACTION_KEY'),
    ],

    'blackhawk' => [
        'base_url' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com'),
        'catalog_api' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com')
            . '/rewardsCatalogProcessing/v1/clientProgram/byKey',
        'realtime_order_api' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com')
            . '/rewardsOrderProcessing/v1/submitRealTimeEgiftBulk',
        'bulk_order_api' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com')
            . '/rewardsOrderProcessing/v1/submitEgiftBulk',

        'retreive_card_api' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com')
            . '/rewardsOrderProcessing/v1/eGiftBulkCodeRetrievalInfo/byKeys',

        'client_program_id' => env('BLACKHAWK_CLIENT_PROGRAM_ID', 95006442),
        'merchant_id' => env('BLACKHAWK_MERCHANT_ID', 60300004707),
        'cert' => env('BLACKHAWK_CERT', $blackhawk_cert_url),
        'cert_password' => env('BLACKHAWK_CERT_PASSWORD', $blackhawk_cert_pw),
    ],
];
