<?php

require_once '../vendor/autoload.php';
require __DIR__ . '/envLoader.php';

loadEnvironmentVariables(__DIR__ . '/../');

use Crittora\CrittoraSDK;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'] ?? getenv('CRITTORA_USERNAME');
$password = $data['password'] ?? getenv('CRITTORA_PASSWORD');
$accessKey = getenv('CRITTORA_ACCESS_KEY');
$secretKey = getenv('CRITTORA_SECRET_KEY');

try {
    $sdk = new CrittoraSDK($accessKey, $secretKey);
    $authResponse = $sdk->authenticate($username, $password);
    echo json_encode(['success' => true, 'IdToken' => $authResponse['IdToken']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}