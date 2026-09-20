<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Testdata\Limits\Context\Session\Limits;

use N1ebieski\KSEFClient\Requests\Testdata\Limits\Context\Session\Limits\LimitsRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Limits\Context\Session\Limits\LimitsRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Limits\Context\Session\Limits\LimitsResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{LimitsRequestFixture, LimitsResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new LimitsRequestFixture(),
    ];

    $responses = [
        new LimitsResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{LimitsRequestFixture, LimitsResponseFixture}> */
    return $combinations;
});

test('valid response', function (LimitsRequestFixture $requestFixture, LimitsResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = LimitsRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->testdata()->limits()->context()->session()->limits($requestFixture->data)->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new LimitsRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->testdata()->limits()->context()->session()->limits($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
