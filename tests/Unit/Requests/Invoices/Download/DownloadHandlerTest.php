<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Invoices\Download;

use N1ebieski\KSEFClient\Requests\Invoices\Download\DownloadRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Download\DownloadRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Download\DownloadResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{DownloadRequestFixture, DownloadResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new DownloadRequestFixture(),
    ];

    $responses = [
        new DownloadResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{DownloadRequestFixture, DownloadResponseFixture}> */
    return $combinations;
});

test('valid response', function (DownloadRequestFixture $requestFixture, DownloadResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = DownloadRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->invoices()->download($requestFixture->data)->body();

    expect($response)->toBe($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new DownloadRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->invoices()->download($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
