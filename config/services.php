<?php

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
use Aws\Sts\StsClient;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log; // Import Laravel's Log facade

// // // Create a S3Client
// $s3 = new S3Client([
//     'version' => 'latest',
//     'region' => 'us-east-2', // Update with your region
// ]);

// $bucketName = 'panda-prod-certs'; // Your S3 bucket name
// $key = 'stag.p12'; // The key of the file in the S3 bucket

// // // Define the path to save the file locally in the secure directory
// $saveAs = storage_path("/secure");

// // Check if the directory exists, if not create it

// // Define the path for the new 'secure' directory inside the 'storage' folder
// $directory = storage_path('secure-test');

// try {
//     // Check if the directory exists, if not, create it
//     if (!file_exists($directory)) {
//         if (mkdir($directory, 0750, true)) { // 0750 permission, true for recursive creation
//             Log::info("Successfully created directory: {$directory}\n");
//         } else {
//             Log::error("Failed to create directory: {$directory}\n");
//         }
//     } else {
//         Log::info("Directory already exists: {$directory}\n");
//     }
// } catch (Exception $e) {
//     Log::error("An error occurred: " . $e->getMessage() . "\n");
// }

// try {
//     // Download the file from S3 and save it locally
//     $s3->getObject([
//         'Bucket' => $bucketName,
//         'Key' => $key,
//         'SaveAs' => $directory . '/' . $key
//     ]);
//     Log::info("File downloaded successfully to {$directory}");
// } catch (AwsException $e) {
//     // Log the error message if something goes wrong
//     Log::error("Error downloading file: " . $e->getMessage());
// }



$blackhawk_cert_pw = null;
$blackhawk_cert_url = null;

$cardknox_customer_id = null; // This is not needed so we are not defining it in else block
$cardknox_ifields_key = null;
$cardknox_transaction_key = null;

if (env("APP_ENV") === "production") {
    $blackhawk_cert_url = storage_path("secure/stag.p12");
    $stsClient = new StsClient([
        'version' => 'latest',
        'region' => "us-east-2"
    ]);

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
    try {
        $response = $secretsManagerClient->getSecretValue([
            'SecretId' => 'CardknoxProd',
            'VersionStage' => 'AWSCURRENT'
        ]);

        if (isset($response['SecretString'])) {
            $foo = json_decode($response['SecretString'], true);
            $cardknox_customer_id = $foo["CARDKNOX_CUSTOMER_ID"];
            $cardknox_ifields_key = $foo["CARDKNOX_IFIELDS_KEY"];
            $cardknox_transaction_key = $foo["CARDKNOX_TRANSACTION_KEY"];
        }
    } catch (Exception $e) {
        Log::error("cardknox credentials not set " . $e->getMessage());
    }
} else {
    $blackhawk_cert_pw =  env('BLACKHAWK_CERT_PASSWORD');
    $blackhawk_cert_url = env('BLACKHAWK_CERT');

    $cardknox_ifields_key = env('CARDKNOX_IFIELDS_KEY');
    $cardknox_transaction_key = env('CARDKNOX_TRANSACTION_KEY');
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
            'key' => $cardknox_ifields_key,
            'version' => '2.15.2302.0801',
        ],
        'transaction_key' => $cardknox_transaction_key,
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
        'cert' => $blackhawk_cert_url,
        'cert_password' => $blackhawk_cert_pw,
    ],
];
