<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Requests\Testdata\Certificates\Update\UpdateRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Certificates\Update\UpdateRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Certificates\Update\UpdateResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{UpdateRequestFixture, UpdateResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new UpdateRequestFixture(),
    ];

    $responses = [
        new UpdateResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{UpdateRequestFixture, UpdateResponseFixture}> */
    return $combinations;
});

test('valid response', function (UpdateRequestFixture $requestFixture, UpdateResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = UpdateRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->testdata()->certificates()->update($requestFixture->data)->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new UpdateRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->testdata()->certificates()->update($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
