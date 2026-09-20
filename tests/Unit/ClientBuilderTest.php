<?php

declare(strict_types=1);

use Mockery\MockInterface;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\RefreshToken;

/** @var AbstractTestCase $this */

test('If custom exception handler is passed to the client resource', function (): void {
    /** @var AbstractTestCase $this */

    /** @var MockInterface&ExceptionHandlerInterface $exceptionHandler */
    $exceptionHandler = Mockery::mock(ExceptionHandlerInterface::class);

    $client = new ClientBuilder();
    $client->withExceptionHandler($exceptionHandler);

    $clientResource = $client->build();

    $reflection = new ReflectionClass($clientResource);
    $property = $reflection->getProperty('exceptionHandler');

    expect($property->getValue($clientResource))->toBe($exceptionHandler);
});

test('If access token and refresh token with validity dates are passed to the builder', function (): void {
    /** @var AbstractTestCase $this */

    $accessToken = 'access-token';
    $refreshToken = 'refresh-token';
    $accessTokenValidUntil = new DateTimeImmutable()->modify(sprintf('+%d days +%d minutes', random_int(1, 365), random_int(0, 1440)));
    $refreshTokenValidUntil = new DateTimeImmutable()->modify(sprintf('+%d days +%d minutes', random_int(366, 730), random_int(0, 1440)));

    $clientResource = new ClientBuilder()
        ->withAccessToken($accessToken, $accessTokenValidUntil)
        ->withRefreshToken($refreshToken, $refreshTokenValidUntil)
        ->build();

    expect($clientResource->getAccessToken())->toBeInstanceOf(AccessToken::class)
        ->and($clientResource->getAccessToken()?->token)->toBe($accessToken)
        ->and($clientResource->getAccessToken()?->validUntil?->getTimestamp())->toBe($accessTokenValidUntil->getTimestamp())
        ->and($clientResource->getRefreshToken())->toBeInstanceOf(RefreshToken::class)
        ->and($clientResource->getRefreshToken()?->token)->toBe($refreshToken)
        ->and($clientResource->getRefreshToken()?->validUntil?->getTimestamp())->toBe($refreshTokenValidUntil->getTimestamp());
});
