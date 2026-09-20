<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Testdata\Context\Block;

use N1ebieski\KSEFClient\Requests\Testdata\Context\Block\BlockRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Context\Block\BlockRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Context\Block\BlockResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{BlockRequestFixture, BlockResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new BlockRequestFixture(),
    ];

    $responses = [
        new BlockResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{BlockRequestFixture, BlockResponseFixture}> */
    return $combinations;
});

test('valid response', function (BlockRequestFixture $requestFixture, BlockResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = BlockRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->testdata()->context()->block($requestFixture->data)->status();

    expect($response)->toEqual($responseFixture->statusCode);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new BlockRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->testdata()->context()->block($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
