<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Sessions\Batch\OpenAndSend;

use N1ebieski\KSEFClient\HttpClient\Response;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\AbstractFakturaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Batch\OpenAndSend\OpenAndSendRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Batch\OpenAndSend\OpenAndSendResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Batch\OpenAndSend\SendResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use RuntimeException;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{OpenAndSendRequestFixture, OpenAndSendResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new OpenAndSendRequestFixture()->withFakturaFixtures(array_map(
            fn (): AbstractFakturaFixture => new FakturaSprzedazyTowaruFixture()
                ->withTodayDate()
                ->withRandomInvoiceNumber(),
            range(1, 3)
        )),
    ];

    $responses = [
        new OpenAndSendResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{OpenAndSendRequestFixture, OpenAndSendResponseFixture}> */
    return $combinations;
});

test('valid response', function (OpenAndSendRequestFixture $requestFixture, OpenAndSendResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $encryptedKey = EncryptedKey::from('string', 'string');

    $httpClientStub = $this->createHttpClientStubWithFixture($responseFixture);
    $httpClientStub->shouldReceive('sendAsyncRequest')
        ->andReturn([new Response($this->createResponseStubWithFixture(new SendResponseFixture()))]);

    $clientStub = $this->createClientStub($httpClientStub)->withEncryptedKey($encryptedKey);

    $request = OpenAndSendRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->sessions()->batch()->openAndSend($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response without EncryptedKey', function (): void {
    /** @var AbstractTestCase $this */
    $requestFixture = new OpenAndSendRequestFixture()->withFakturaFixtures(array_map(
        fn (): AbstractFakturaFixture => new FakturaSprzedazyTowaruFixture()
            ->withTodayDate()
            ->withRandomInvoiceNumber(),
        range(1, 3)
    ));
    $responseFixture = new OpenAndSendResponseFixture();

    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $clientStub->sessions()->batch()->openAndSend($requestFixture->data)->object();
})->throws(RuntimeException::class, 'Encrypted key is required to open session.');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new OpenAndSendRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->sessions()->batch()->openAndSend($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
