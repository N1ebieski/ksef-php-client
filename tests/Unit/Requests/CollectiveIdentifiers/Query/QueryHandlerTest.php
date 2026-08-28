<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Query\QueryRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Query\QueryRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Query\QueryResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
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

    expect($request->toHeaders())
        ->toHaveKey('x-continuation-token')
        ->toContain($requestFixture->data['continuationToken']);

    expect($request->toParameters())->toBe(['pageSize' => $requestFixture->data['pageSize']]);

    $response = $clientStub->collectiveIdentifiers()->query($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new QueryRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->collectiveIdentifiers()->query($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
