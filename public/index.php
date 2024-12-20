<?php

require __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Add these lines
foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}

// Get username and password from environment variables
$username = getenv('CRITTORA_USERNAME');
$password = getenv('CRITTORA_PASSWORD');



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Crittora SDK Demo</title>
</head>
<body>
    <h1>Crittora SDK Demo</h1>
    <p id="timestamp"></p>
    <div>
        <h2>Authenticate</h2>
        <input type="text" id="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
        <input type="password" id="password" placeholder="Password" value="<?php echo htmlspecialchars($password); ?>">
        <button onclick="authenticate()">Authenticate</button>
        <p id="tokenDisplay"></p>
    </div>
    <div>
        <h2>Encrypt Data</h2>
        <input type="text" id="dataToEncrypt" placeholder="Data to encrypt">
        <button onclick="encryptData()">Encrypt</button>
        <p id="encryptMessage"></p>
    </div>
    <div class="results">
        <label>Encrypted Result:</label>
        <pre id="encryptedResult"></pre>
    </div>
    <div>
        <h2>Decrypt Data</h2>
        <input type="text" id="encryptedData" placeholder="Encrypted data">
        <button onclick="decryptData()">Decrypt</button>
        <p id="decryptMessage"></p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const timestampElement = document.getElementById('timestamp');
            const now = new Date();
            timestampElement.textContent = `Page loaded on: ${now.toLocaleString()}`;
        });

        let idToken = '';

        // Set username and password from PHP variables
        const username = "<?php echo $username; ?>";
        const password = "<?php echo $password; ?>";

        function authenticate() {
            // Use the username and password from the environment
            fetch('authenticate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Authentication successful');
                    idToken = data.IdToken;
                    document.getElementById('tokenDisplay').textContent = `IdToken: ${idToken}`;
                } else {
                    console.error('Authentication failed:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function encryptData() {

            const data = document.getElementById('dataToEncrypt').value;
            const permissions = ['read', 'write']; // Example permissions
            console.log('idToken: ', idToken);
            console.log('data: ', data);
            console.log('permissions: ', permissions);
            fetch('encrypt.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ idToken, data, permissions })
            })
            .then(response => response.json())
            .then(data => {
                const messageElement = document.getElementById('encryptMessage');
                const encryptedDataDisplay = document.getElementById('encryptedResult');
                if (data.success) {
                    console.log('Encrypted data:', data.encryptedData);
                    messageElement.textContent = 'Encryption successful!';
                    encryptedDataDisplay.textContent = data.encryptedData; // Display encrypted data
                } else {
                    console.error('Encryption failed:', data.error);
                    messageElement.textContent = `Encryption failed: ${data.error}`;
                    encryptedDataDisplay.textContent = ''; // Clear display on failure
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('encryptMessage').textContent = `Error: ${error}`;
                document.getElementById('encryptedResult').textContent = ''; // Clear display on error
            });
        }

        function decryptData() {
            const encryptedData = document.getElementById('encryptedData').value;
            const permissions = ['read']; // Example permissions
            console.log('idToken: ', idToken);
            console.log('encryptedData: ', encryptedData);
            console.log('permissions: ', permissions);
            fetch('decrypt.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ idToken, encryptedData, permissions })
            })
            .then(response => response.json())
            .then(data => {
                const messageElement = document.getElementById('decryptMessage');
                if (data.success) {
                    console.log('Decrypted data:', data.decryptedData);
                    messageElement.textContent = `Decryption successful! Data: ${data.decryptedData}`;
                } else {
                    console.error('Decryption failed:', data.error);
                    messageElement.textContent = `Decryption failed: ${data.error}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('decryptMessage').textContent = `Error: ${error}`;
            });
        }
    </script>
</body>
</html>