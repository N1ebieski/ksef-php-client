<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Sessions\Upo;

use N1ebieski\KSEFClient\Requests\Sessions\Upo\UpoRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Upo\UpoRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Upo\UpoResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{UpoRequestFixture, UpoResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new UpoRequestFixture(),
    ];

    $responses = [
        new UpoResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{UpoRequestFixture, UpoResponseFixture}> */
    return $combinations;
});

test('valid response', function (UpoRequestFixture $requestFixture, UpoResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = UpoRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->sessions()->upo($requestFixture->data)->body();

    expect($response)->toBe($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new UpoRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->sessions()->upo($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
