<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Latarnik\Messages;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Latarnia\Messages\MessagesResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{MessagesResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $responses = [
        new MessagesResponseFixture(),
    ];

    $combinations = [];

    foreach ($responses as $response) {
        $combinations[$response->name] = [$response];
    }

    /** @var array<string, array{MessagesResponseFixture}> */
    return $combinations;
});

test('valid response', function (MessagesResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $response = $clientStub->latarnia()->messages()->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');
