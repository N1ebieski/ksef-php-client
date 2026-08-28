<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Invoices\InvoicesRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Invoices\InvoicesRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Invoices\InvoicesResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{InvoicesRequestFixture, InvoicesResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new InvoicesRequestFixture(),
    ];

    $responses = [
        new InvoicesResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{InvoicesRequestFixture, InvoicesResponseFixture}> */
    return $combinations;
});

test('valid response', function (InvoicesRequestFixture $requestFixture, InvoicesResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = InvoicesRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    expect($request->toHeaders())
        ->toHaveKey('x-continuation-token')
        ->toContain($requestFixture->data['continuationToken']);

    expect($request->toParameters())->toBe(['pageSize' => $requestFixture->data['pageSize']]);

    expect($request->toBody())->toBe([
        'collectiveIdentifierNumbers' => $requestFixture->data['collectiveIdentifierNumbers'],
    ]);

    $response = $clientStub->collectiveIdentifiers()->invoices($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new InvoicesRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->collectiveIdentifiers()->invoices($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
