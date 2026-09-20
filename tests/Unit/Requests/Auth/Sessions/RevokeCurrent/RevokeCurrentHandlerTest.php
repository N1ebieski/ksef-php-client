<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Auth\Sessions\RevokeCurrent;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Auth\Sessions\RevokeCurrent\RevokeCurrentResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{RevokeCurrentResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $responses = [
        new RevokeCurrentResponseFixture(),
    ];

    $combinations = [];

    foreach ($responses as $response) {
        $combinations[$response->name] = [$response];
    }

    /** @var array<string, array{RevokeCurrentResponseFixture}> */
    return $combinations;
});

test('valid response', function (RevokeCurrentResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $response = $clientStub->auth()->sessions()->revokeCurrent()->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->auth()->sessions()->revokeCurrent();
    })->toBeExceptionFixture($responseFixture->data);
});
