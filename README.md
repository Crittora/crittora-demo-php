# Crittora SDK PHP Demo

This project is a demonstration of how to use the Crittora SDK for PHP. It provides a simple web interface for authentication, data encryption, and decryption using the Crittora SDK.

## Features

- User authentication using environment variables.
- Data encryption and decryption.
- User-friendly web interface built with Bootstrap.

## Requirements

- PHP 7.2 or higher
- Composer
- Access to the Crittora SDK

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Crittora/crittora-demo-php.git
   cd crittora-demo-php
   ```

2. Install dependencies using Composer:

   ```bash
   composer install
   ```

3. Create a `.env` file in the root directory and add your environment variables:

   ```plaintext
   CRITTORA_USERNAME=your_username
   CRITTORA_PASSWORD=your_password
   AWS_ACCESS_KEY_ID=your_access_key
   AWS_SECRET_ACCESS_KEY=your_secret_key
   ```

4. Start a local PHP server:

   ```bash
   php -S localhost:8000 -t public
   ```

5. Open your browser and navigate to `http://localhost:8000`.

## Usage

- **Authenticate**: Enter your username and password to authenticate and receive an ID token.
- **Encrypt Data**: Input the data you want to encrypt and click the "Encrypt" button.
- **Decrypt Data**: Input the encrypted data and click the "Decrypt" button to retrieve the original data.

## Code Structure

- `public/`: Contains the main application files.
  - `index.php`: The main entry point of the application.
  - `authenticate.php`: Handles user authentication.
  - `encrypt.php`: Handles data encryption.
  - `decrypt.php`: Handles data decryption.
  - `envLoader.php`: Loads environment variables.
- `vendor/`: Contains Composer dependencies.
- `.env`: Environment variables file (not included in version control).
- `composer.json`: Composer configuration file.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.
