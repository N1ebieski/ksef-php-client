<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Sessions\Online\Open;

use N1ebieski\KSEFClient\Requests\Sessions\Online\Open\OpenRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Open\OpenRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Open\OpenResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use RuntimeException;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{OpenRequestFixture, OpenResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new OpenRequestFixture(),
    ];

    $responses = [
        new OpenResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{OpenRequestFixture, OpenResponseFixture}> */
    return $combinations;
});
test('valid response', function (OpenRequestFixture $requestFixture, OpenResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $encryptedKey = EncryptedKey::from('string', 'string');

    $clientStub = $this->createClientStubWithFixture($responseFixture)->withEncryptedKey($encryptedKey);

    $request = OpenRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->sessions()->online()->open($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response without EncryptedKey', function (): void {
    /** @var AbstractTestCase $this */
    $requestFixture = new OpenRequestFixture();
    $responseFixture = new OpenResponseFixture();

    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $clientStub->sessions()->online()->open($requestFixture->data)->object();
})->throws(RuntimeException::class, 'Encrypted key is required to open session.');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new OpenRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->sessions()->online()->open($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
