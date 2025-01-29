# Turnstile PHP Validator

This package provides a simple way to validate Cloudflare Turnstile CAPTCHA tokens using PHP and Guzzle. It allows you to integrate Cloudflare's CAPTCHA verification into your server-side application.

## Installation

To install this package, use Composer:

```bash
composer require toolslib/turnstile-php
```

## Requirements

- PHP 7.4 or higher
- Guzzle HTTP Client (automatically installed with Composer)

## Configuration

To use the validator, you need to have a valid Cloudflare Turnstile secret key. You can obtain this key from the [Cloudflare Turnstile Dashboard](https://developers.cloudflare.com/turnstile/).

## Usage

### 1. Set Up the Validator

First, create an instance of the `Validator` class and pass your secret key:

```php
use ToolsLib\TurnstilePhp\Validator;

// Your Cloudflare Turnstile secret key
$secretKey = 'your-secret-key';

// Initialize the validator
$validator = new Validator($secretKey);
```

### 2. Validate the Turnstile Token

Next, you can use the `validateTurnstileToken` method to validate a CAPTCHA response from the user. This method requires the Turnstile token that was received from the client-side as well as an optional IP address (`CF-Connecting-IP` header from Cloudflare):

```php
// Get the Turnstile response token from the form submission
$token = $_POST['cf-turnstile-response'];

// Get the user's IP address (optional, but recommended for extra validation)
$ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null;

// Validate the token
$isValid = $validator->validateTurnstileToken($token, $ip);

// Check the result
if ($isValid) {
    // The CAPTCHA token is valid, proceed with your logic
    echo "CAPTCHA validation succeeded.";
} else {
    // The CAPTCHA token is invalid, handle the failure
    echo "CAPTCHA validation failed.";
}
```

### 3. Handle Errors

In case of an error (e.g., network issues, invalid response from Cloudflare), the method will return `false`. You can handle this by either logging the error or showing an appropriate message to the user.

```php
try {
    // Validate the token
    $isValid = $validator->validateTurnstileToken($token, $ip);

    if ($isValid) {
        // CAPTCHA validation succeeded, proceed with your logic
        echo "CAPTCHA validation succeeded.";
    }
} catch (ToolsLib\TurnstilePhp\TurnstileValidationException $e) {
    // Handle the exception if validation fails
    echo "Error: " . $e->getMessage();
}
```

## License

This package is open-source and available under the GNU GPL v3 license.

## Contributing

Contributions are welcome! Please feel free to fork the repository, create a new branch, and submit a pull request. For major changes, please open an issue first to discuss what you would like to change.
