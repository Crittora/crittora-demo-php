<?php

require_once '../vendor/autoload.php'; // Adjust the path as necessary

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use Crittora\CrittoraSDK;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Log the username and password (be cautious with this in production)
error_log("Username: $username, Password: $password");

try {
    $sdk = new CrittoraSDK();
    $authResponse = $sdk->authenticate($username, $password);
    error_log("Auth Response: " . print_r($authResponse, true));
    echo json_encode(['success' => true, 'IdToken' => $authResponse['IdToken']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 