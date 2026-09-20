<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Invoices\Exports\Init;

use N1ebieski\KSEFClient\Requests\Invoices\Exports\Init\InitRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Exports\Init\InitRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Exports\Init\InitResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use RuntimeException;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{InitRequestFixture, InitResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new InitRequestFixture(),
    ];

    $responses = [
        new InitResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{InitRequestFixture, InitResponseFixture}> */
    return $combinations;
});

test('valid response', function (InitRequestFixture $requestFixture, InitResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $encryptedKey = EncryptedKey::from('string', 'string');

    $clientStub = $this->createClientStubWithFixture($responseFixture)->withEncryptedKey($encryptedKey);

    $request = InitRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->invoices()->exports()->init($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response without EncryptedKey', function (): void {
    /** @var AbstractTestCase $this */
    $requestFixture = new InitRequestFixture();
    $responseFixture = new InitResponseFixture();

    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $clientStub->invoices()->exports()->init($requestFixture->data)->object();
})->throws(RuntimeException::class, 'Encrypted key is required to open session.');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new InitRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->invoices()->exports()->init($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
