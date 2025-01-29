<?php

namespace ToolsLib\TurnstilePhp;

use ToolsLib\TurnstilePhp\TurnstileValidationException;
use GuzzleHttp\Client;

class Validator
{
    const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    private string $secretKey;

    private Client $client;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
        $this->client = new Client();
    }

    /**
     * Summary of validateTurnstileToken
     * 
     * @param string $token
     * @param ?string $ip
     * @return bool
     */
    public function validateTurnstileToken(string $token, ?string $ip = null): bool
    {
        try {
            $response = $this->client->post(self::VERIFY_URL, [
                'form_params' => [
                    'secret' => $this->secretKey,
                    'response' => $token,
                ] + ($ip ? ['remoteip' => $ip] : []),
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            return isset($responseBody['success']) && $responseBody['success'] === true;
        } catch (\Exception $e) {
            throw new TurnstileValidationException('An error occurred during Turnstile validation: ' . $e->getMessage(), 0, $e);
        }
    }
}
