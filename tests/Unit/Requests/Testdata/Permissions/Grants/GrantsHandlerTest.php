<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Testdata\Permissions\Grants;

use N1ebieski\KSEFClient\Requests\Testdata\Permissions\Grants\GrantsRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Permissions\Grants\GrantsRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Permissions\Grants\GrantsResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{GrantsRequestFixture, GrantsResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new GrantsRequestFixture(),
    ];

    $responses = [
        new GrantsResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{GrantsRequestFixture, GrantsResponseFixture}> */
    return $combinations;
});

test('valid response', function (GrantsRequestFixture $requestFixture, GrantsResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = GrantsRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->testdata()->permissions()->grants($requestFixture->data)->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new GrantsRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->testdata()->permissions()->grants($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
