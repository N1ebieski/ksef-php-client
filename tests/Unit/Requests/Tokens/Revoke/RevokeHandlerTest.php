<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Tokens\Revoke;

use N1ebieski\KSEFClient\Requests\Tokens\Revoke\RevokeRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Tokens\Revoke\RevokeRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Tokens\Revoke\RevokeResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{RevokeRequestFixture, RevokeResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new RevokeRequestFixture(),
    ];

    $responses = [
        new RevokeResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{RevokeRequestFixture, RevokeResponseFixture}> */
    return $combinations;
});

test('valid response', function (RevokeRequestFixture $requestFixture, RevokeResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = RevokeRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->tokens()->revoke($requestFixture->data)->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new RevokeRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->tokens()->revoke($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
