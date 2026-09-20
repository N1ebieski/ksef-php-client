<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Limits\Subject;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Limits\Subject\SubjectResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{SubjectResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $responses = [
        new SubjectResponseFixture(),
    ];

    $combinations = [];

    foreach ($responses as $response) {
        $combinations[$response->name] = [$response];
    }

    /** @var array<string, array{SubjectResponseFixture}> */
    return $combinations;
});

test('valid response', function (SubjectResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $response = $clientStub->limits()->subject()->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->limits()->subject();
    })->toBeExceptionFixture($responseFixture->data);
});
