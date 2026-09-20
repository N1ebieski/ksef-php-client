<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Resources;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Auth\Token\Refresh\RefreshResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\RefreshToken;
use RuntimeException;

/** @var AbstractTestCase $this */

/**
 * @return array<int, array<int, string>>
 */
dataset('resourceProvider', fn (): array => [
    ['auth'],
    ['limits'],
    ['sessions'],
    ['invoices'],
    ['permissions'],
    ['certificates'],
    ['tokens']
]);

test('auto access token refresh', function (string $resource): void {
    /** @var AbstractTestCase $this */
    $responseFixture = new RefreshResponseFixture()->withValidUntil(new DateTimeImmutable('+15 minutes'));

    $accessToken = new AccessToken('access-token', new DateTimeImmutable('-15 minutes'));
    $refreshToken = new RefreshToken('refresh-token', new DateTimeImmutable('+7 days'));

    $clientStub = $this->createClientStubWithFixture($responseFixture)
        ->withAccessToken($accessToken)
        ->withRefreshToken($refreshToken);

    $clientStub->{$resource}();

    /** @var AccessToken $newAccessToken */
    $newAccessToken = $clientStub->getAccessToken();

    expect($newAccessToken->isEquals($accessToken))->toBeFalse();

    expect($newAccessToken->isEquals($responseFixture->getAccessToken()))->toBeTrue();
})->with('resourceProvider');

test('throw exception if access token is expired', function (string $resource): void {
    /** @var AbstractTestCase $this */
    $accessToken = new AccessToken('access-token', new DateTimeImmutable('-15 minutes'));

    $clientStub = $this->createClientStubWithFixture(new RefreshResponseFixture())
        ->withAccessToken($accessToken);

    $clientStub->{$resource}();
})->with('resourceProvider')->throws(RuntimeException::class, 'Access token and refresh token are expired.');
