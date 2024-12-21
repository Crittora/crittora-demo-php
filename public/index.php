<?php

require __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload
require __DIR__ . '/envLoader.php'; // Adjusted path to include the missing slash

loadEnvironmentVariables(__DIR__ . '/../');

// Validate required environment variables
$requiredVars = ['CRITTORA_USERNAME', 'CRITTORA_PASSWORD', 'AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY'];
foreach ($requiredVars as $var) {
    if (!getenv($var)) {
        error_log("$var is missing");
        throw new Exception("$var environment variable is not set.");
    }
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>Crittora SDK Demo</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Crittora SDK Demo</h1>
        <p id="timestamp"></p>
        <div class="mb-4">
            <h2>Authenticate</h2>
            <input type="text" id="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
            <input type="password" id="password" class="form-control" placeholder="Password" value="<?php echo htmlspecialchars($password); ?>">
            <button class="btn btn-primary mt-2" onclick="authenticate()">Authenticate</button>
            <p id="tokenDisplay"></p>
        </div>
        <div class="mb-4">
            <h2>Encrypt Data</h2>
            <input type="text" id="dataToEncrypt" class="form-control" placeholder="Data to encrypt">
            <button class="btn btn-success mt-2" onclick="encryptData()">Encrypt</button>
            <p id="encryptMessage"></p>
        </div>
        <div class="results">
            <label>Encrypted Result:</label>
            <div class="flex-container justify-content-start">
            <pre id="encryptedResult"></pre>
            <div id="spinner" class="spinner-grow" role="status" style="display: none;">
                <span class="sr-only">Loading...</span>
            </div>
            </div>
          
        </div>
        <div class="mb-4">
            <h2>Decrypt Data</h2>
            <input type="text" id="encryptedData" class="form-control" placeholder="Encrypted data">
            <button class="btn btn-danger mt-2" onclick="decryptData()">Decrypt</button>
            <p id="decryptMessage"></p>
            <div id="spinner-decrypt" class="spinner-grow" role="status" style="display: none;">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
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
                    console.log('index.php : Authentication successful');
                    idToken = data.IdToken;
                    document.getElementById('tokenDisplay').textContent = `IdToken: ${idToken}`;
                } else {
                    console.error('index.php : Authentication failed :', data.error);
                }
            })
            .catch(error => console.error('index.php : Error:', error));
        }

        function encryptData() {
            // Show the spinner when encryption starts
            document.getElementById('spinner').style.display = 'block'; // Show spinner

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
                // Hide the spinner when encryption is done
                document.getElementById('spinner').style.display = 'none'; // Hide spinner
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
                // Hide the spinner on error
                document.getElementById('spinner').style.display = 'none'; // Hide spinner
            });
        }

        function decryptData() {
            // Show the spinner when encryption starts
            document.getElementById('spinner-decrypt').style.display = 'block'; // Show spinner

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
                // Hide the spinner when encryption is done
                document.getElementById('spinner-decrypt').style.display = 'none'; // Hide spinner
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