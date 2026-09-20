<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Sessions\Online\Close;

use N1ebieski\KSEFClient\Requests\Sessions\Online\Close\CloseRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Close\CloseRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Close\CloseResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{CloseRequestFixture, CloseResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new CloseRequestFixture(),
    ];

    $responses = [
        new CloseResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{CloseRequestFixture, CloseResponseFixture}> */
    return $combinations;
});

test('valid response', function (CloseRequestFixture $requestFixture, CloseResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = CloseRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->sessions()->online()->close($requestFixture->data)->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new CloseRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->sessions()->online()->close($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
