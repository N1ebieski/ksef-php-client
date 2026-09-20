<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Permissions\Common\Revoke;

use N1ebieski\KSEFClient\Requests\Permissions\Common\Revoke\RevokeRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Permissions\Common\Revoke\RevokeRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Permissions\Common\Revoke\RevokeResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{RevokeRequestFixture, RevokeResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        new RevokeRequestFixture(),
    ];

    $responses = [
        new RevokeResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{RevokeRequestFixture, RevokeResponseFixture}> */
    return $combinations;
});

test('valid response', function (RevokeRequestFixture $requestFixture, RevokeResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStubWithFixture($responseFixture);

    $request = RevokeRequest::from($requestFixture->data);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->permissions()->common()->revoke($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        $requestFixture = new RevokeRequestFixture();

        $clientStub = $this->createClientStubWithFixture($responseFixture);

        $clientStub->permissions()->common()->revoke($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
