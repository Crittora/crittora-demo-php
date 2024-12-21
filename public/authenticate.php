<?php

require_once '../vendor/autoload.php'; // Adjust the path as necessary
require __DIR__ . '/envLoader.php'; // Adjusted path to include the missing slash

loadEnvironmentVariables(__DIR__ . '/../');

use Crittora\CrittoraSDK;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Retrieve username, password, access key, and secret key from the request
$username = $data['username'] ?? getenv('CRITTORA_USERNAME');
$password = $data['password'] ?? getenv('CRITTORA_PASSWORD');
$accessKey = $data['accessKey'] ?? getenv('AWS_ACCESS_KEY_ID');
$secretKey = $data['secretKey'] ?? getenv('AWS_SECRET_ACCESS_KEY');

// Log the username, password, access key, and secret key for debugging (remove in production)
error_log("Username: $username, Password: $password, AccessKey: $accessKey, SecretKey: $secretKey");

try {
    // Pass access key and secret key to the CrittoraSDK constructor
    $sdk = new CrittoraSDK($accessKey, $secretKey);
    $authResponse = $sdk->authenticate($username, $password);
    error_log("Auth Response: " . print_r($authResponse, true));
    echo json_encode(['success' => true, 'IdToken' => $authResponse['IdToken']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}