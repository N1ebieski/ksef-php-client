<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Testdata\RateLimits\Production;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\RateLimits\Production\ProductionResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{ProductionResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $responses = [
        new ProductionResponseFixture(),
    ];

    $combinations = [];

    foreach ($responses as $response) {
        $combinations[$response->name] = [$response];
    }

    /** @var array<string, array{ProductionResponseFixture}> */
    return $combinations;
});

test('valid response', function (ProductionResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $response = $clientStub->testdata()->rateLimits()->production()->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->testdata()->rateLimits()->production();
    })->toBeExceptionFixture($responseFixture->data);
});
