<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Peppol\Query;

use N1ebieski\KSEFClient\Requests\Peppol\Query\QueryRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Peppol\Query\QueryRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Peppol\Query\QueryResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{QueryRequestFixture, QueryResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new QueryRequestFixture(),
    ];

    $responses = [
        new QueryResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{QueryRequestFixture, QueryResponseFixture}> */
    return $combinations;
});

test('valid response', function (QueryRequestFixture $requestFixture, QueryResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = QueryRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->peppol()->query($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new QueryRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->peppol()->query($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
