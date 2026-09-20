<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Invoices\Exports\Status;

use N1ebieski\KSEFClient\Requests\Invoices\Exports\Status\StatusRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Exports\Status\StatusRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Exports\Status\StatusResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{StatusRequestFixture, StatusResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new StatusRequestFixture(),
    ];

    $responses = [
        new StatusResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{StatusRequestFixture, StatusResponseFixture}> */
    return $combinations;
});

test('valid response', function (StatusRequestFixture $requestFixture, StatusResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = StatusRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->invoices()->exports()->status($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new StatusRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->invoices()->exports()->status($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
