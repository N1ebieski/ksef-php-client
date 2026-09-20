<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Latarnik\Status;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Latarnia\Status\StatusResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{StatusResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $responses = [
        new StatusResponseFixture(),
    ];

    $combinations = [];

    foreach ($responses as $response) {
        $combinations[$response->name] = [$response];
    }

    /** @var array<string, array{StatusResponseFixture}> */
    return $combinations;
});

test('valid response', function (StatusResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $response = $clientStub->latarnia()->status()->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');
