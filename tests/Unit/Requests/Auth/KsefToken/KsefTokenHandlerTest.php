<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Auth\KsefToken;

use N1ebieski\KSEFClient\Requests\Auth\KsefToken\KsefTokenRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Auth\KsefToken\KsefTokenRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Auth\KsefToken\KsefTokenResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{KsefTokenRequestFixture, KsefTokenResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new KsefTokenRequestFixture(),
    ];

    $responses = [
        new KsefTokenResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{KsefTokenRequestFixture, KsefTokenResponseFixture}> */
    return $combinations;
});

test('valid response', function (KsefTokenRequestFixture $requestFixture, KsefTokenResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = KsefTokenRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->auth()->ksefToken($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new KsefTokenRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->auth()->ksefToken($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
