<?php

require_once '../vendor/autoload.php';
require __DIR__ . '/envLoader.php'; // Adjusted path to include the missing slash

loadEnvironmentVariables(__DIR__ . '/../');

use Crittora\CrittoraSDK;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$idToken = $data['idToken'] ?? '';
$dataToEncrypt = $data['data'] ?? '';
$permissions = $data['permissions'] ?? '';

try {
    $sdk = new CrittoraSDK();
    $encryptedData = $sdk->encrypt($idToken, $dataToEncrypt);
    echo json_encode(['success' => true, 'encryptedData' => $encryptedData]);
} catch (CrittoraException $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage(),
        'errorCode' => $e->getErrorCode()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'An unexpected error occurred']);
}