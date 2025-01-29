<?php

use PHPUnit\Framework\TestCase;
use ToolsLib\TurnstilePhp\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use ToolsLib\TurnstilePhp\TurnstileValidationException;

class ValidatorTest extends TestCase
{
    private string $secretKey = 'test-secret-key';
    private Validator $validator;
    private $mockClient;

    protected function setUp(): void
    {
        // Mock the Guzzle client
        $this->mockClient = $this->createMock(Client::class);

        // Instantiate the Validator with the mock client
        $this->validator = new Validator($this->secretKey);
    }

    public function testValidateTurnstileTokenSuccess()
    {
        $token = 'valid-token';
        $ip = '192.168.1.1';

        // Mock the Guzzle client to return a successful response
        $this->mockClient
            ->method('post')
            ->willReturn(new Response(200, [], '{"success": true}'));

        $this->validator = new Validator($this->secretKey, $this->mockClient);

        $result = $this->validator->validateTurnstileToken($token, $ip);

        $this->assertTrue($result);
    }

    public function testValidateTurnstileTokenFailure()
    {
        $token = 'invalid-token';
        $ip = '192.168.1.1';

        // Mock the Guzzle client to return a failure response
        $this->mockClient
            ->method('post')
            ->willReturn(new Response(200, [], '{"success": false}'));

        $this->validator = new Validator($this->secretKey, $this->mockClient);

        $result = $this->validator->validateTurnstileToken($token, $ip);

        $this->assertFalse($result);
    }

    public function testValidateTurnstileTokenException()
    {
        $token = 'invalid-token';
        $ip = '192.168.1.1';

        // Mock the Guzzle client to throw an exception (e.g., network error)
        $this->mockClient
            ->method('post')
            ->willThrowException(new RequestException('Request failed', new \GuzzleHttp\Psr7\Request('POST', 'test')));

        $this->validator = new Validator($this->secretKey, $this->mockClient);

        $this->expectException(TurnstileValidationException::class);

        // Attempting to validate the token will throw a TurnstileValidationException
        $this->validator->validateTurnstileToken($token, $ip);
    }
}
