<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Testdata\Limits\Subject\Certificate\Reset;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Limits\Subject\Certificate\Reset\ResetResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{ResetResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $responses = [
        new ResetResponseFixture(),
    ];

    $combinations = [];

    foreach ($responses as $response) {
        $combinations[$response->name] = [$response];
    }

    /** @var array<string, array{ResetResponseFixture}> */
    return $combinations;
});

test('valid response', function (ResetResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $response = $clientStub->testdata()->limits()->subject()->certificate()->reset()->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->testdata()->limits()->subject()->certificate()->reset();
    })->toBeExceptionFixture($responseFixture->data);
});
