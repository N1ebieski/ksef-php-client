<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Testdata\Context\Unblock;

use N1ebieski\KSEFClient\Requests\Testdata\Context\Unblock\UnblockRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Context\Unblock\UnblockRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Context\Unblock\UnblockResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{UnblockRequestFixture, UnblockResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new UnblockRequestFixture(),
    ];

    $responses = [
        new UnblockResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{UnblockRequestFixture, UnblockResponseFixture}> */
    return $combinations;
});

test('valid response', function (UnblockRequestFixture $requestFixture, UnblockResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = UnblockRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->testdata()->context()->unblock($requestFixture->data)->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new UnblockRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->testdata()->context()->unblock($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
