<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Testdata\Attachment\Approve;

use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Approve\ApproveRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Attachment\Approve\ApproveRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Attachment\Approve\ApproveResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{ApproveRequestFixture, ApproveResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new ApproveRequestFixture(),
    ];

    $responses = [
        new ApproveResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{ApproveRequestFixture, ApproveResponseFixture}> */
    return $combinations;
});

test('valid response', function (ApproveRequestFixture $requestFixture, ApproveResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = ApproveRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->testdata()->attachment()->approve($requestFixture->data)->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new ApproveRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->testdata()->attachment()->approve($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
