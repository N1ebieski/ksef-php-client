<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Invoices\Query\Metadata;

use N1ebieski\KSEFClient\Requests\Invoices\Query\Metadata\MetadataRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Query\Metadata\MetadataRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Query\Metadata\MetadataResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{MetadataRequestFixture, MetadataResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new MetadataRequestFixture(),
    ];

    $responses = [
        new MetadataResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{MetadataRequestFixture, MetadataResponseFixture}> */
    return $combinations;
});

test('valid response', function (MetadataRequestFixture $requestFixture, MetadataResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = MetadataRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->invoices()->query()->metadata($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new MetadataRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->invoices()->query()->metadata($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
