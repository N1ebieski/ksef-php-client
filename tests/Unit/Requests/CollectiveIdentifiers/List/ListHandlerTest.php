<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\List\ListRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\List\ListRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\List\ListResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{ListRequestFixture, ListResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new ListRequestFixture(),
    ];

    $responses = [
        new ListResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{ListRequestFixture, ListResponseFixture}> */
    return $combinations;
});

test('valid response', function (ListRequestFixture $requestFixture, ListResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = ListRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    expect($request->toHeaders())
        ->toHaveKey('x-continuation-token')
        ->toContain($requestFixture->data['continuationToken']);

    $response = $clientStub->collectiveIdentifiers()->list($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new ListRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->collectiveIdentifiers()->list($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
