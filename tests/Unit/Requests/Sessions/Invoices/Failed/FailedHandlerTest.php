<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Sessions\Invoices\Failed;

use N1ebieski\KSEFClient\Requests\Sessions\Invoices\Failed\FailedRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Invoices\Failed\FailedRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Invoices\Failed\FailedResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{FailedRequestFixture, FailedResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new FailedRequestFixture(),
    ];

    $responses = [
        new FailedResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{FailedRequestFixture, FailedResponseFixture}> */
    return $combinations;
});

test('valid response', function (FailedRequestFixture $requestFixture, FailedResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = FailedRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    expect($request->toHeaders())
        ->toHaveKey('x-continuation-token')
        ->toContain($requestFixture->data['continuationToken']);

    $response = $clientStub->sessions()->invoices()->failed($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new FailedRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->sessions()->invoices()->failed($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
