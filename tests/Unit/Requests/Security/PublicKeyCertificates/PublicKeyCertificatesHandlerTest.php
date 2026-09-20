<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Requests\Security\PublicKeyCertificates;

use Mockery;
use Mockery\MockInterface;
use N1ebieski\KSEFClient\Contracts\ConfigInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\HttpClient\Response;
use N1ebieski\KSEFClient\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesResponse;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use Psr\SimpleCache\CacheInterface;

/** @var AbstractTestCase $this */

test('returns public key certificates from cache when available', function (): void {
    /** @var AbstractTestCase $this */
    /** @var MockInterface&HttpClientInterface $httpClientStub */
    $httpClientStub = Mockery::mock(HttpClientInterface::class);
    $httpClientStub->shouldReceive('withoutAccessToken')->never();
    $httpClientStub->shouldReceive('sendRequest')->never();

    $responseFixture = new PublicKeyCertificatesResponseFixture();

    $cachedResponse = new PublicKeyCertificatesResponse(
        new Response($this->createResponseStubWithFixture($responseFixture))
    );

    /** @var MockInterface&CacheInterface $cacheStub */
    $cacheStub = Mockery::mock(CacheInterface::class);
    $cacheStub->shouldReceive('get')->once()->andReturn($cachedResponse);
    $cacheStub->shouldReceive('set')->never();

    $response = $this->createClientStub($httpClientStub, $cacheStub)
        ->security()
        ->publicKeyCertificates();

    expect($response)->toBe($cachedResponse);
});

test('fetches public key certificates from source when cache is null', function (): void {
    /** @var AbstractTestCase $this */
    $responseFixture = new PublicKeyCertificatesResponseFixture();

    $httpResponse = new Response($this->createResponseStubWithFixture($responseFixture));

    /** @var MockInterface&HttpClientInterface $httpClientStub */
    $httpClientStub = Mockery::mock(HttpClientInterface::class);
    $httpClientStub->shouldReceive('withoutAccessToken')->once()->andReturnSelf();
    $httpClientStub->shouldReceive('sendRequest')->once()->andReturn($httpResponse);

    $response = $this->createClientStub($httpClientStub)
        ->security()
        ->publicKeyCertificates();

    expect($response)->toBeInstanceOf(PublicKeyCertificatesResponse::class)
        ->and($response->object())->toHaveCount(1);
});

test('fetches from source and saves to cache when cache has no value', function (): void {
    /** @var AbstractTestCase $this */
    $responseFixture = new PublicKeyCertificatesResponseFixture();

    $httpResponse = new Response($this->createResponseStubWithFixture($responseFixture));

    /** @var MockInterface&HttpClientInterface $httpClientStub */
    $httpClientStub = Mockery::mock(HttpClientInterface::class);
    $httpClientStub->shouldReceive('withoutAccessToken')->once()->andReturnSelf();
    $httpClientStub->shouldReceive('sendRequest')->once()->andReturn($httpResponse);

    /** @var MockInterface&CacheInterface $cacheStub */
    $cacheStub = Mockery::mock(CacheInterface::class);
    $cacheStub->shouldReceive('get')->once()->andReturn(null);
    $cacheStub->shouldReceive('set')
        ->once()
        ->withArgs(fn (string $key, mixed $value, int $ttl): bool =>
            $key === sprintf(ConfigInterface::PUBLIC_KEY_CERTIFICATES_CACHE_KEY, Mode::Test->value)
                && $value instanceof PublicKeyCertificatesResponse
                && $ttl === 43200)
        ->andReturn(true);

    $response = $this->createClientStub($httpClientStub, $cacheStub)
        ->security()
        ->publicKeyCertificates();

    expect($response)->toBeInstanceOf(PublicKeyCertificatesResponse::class)
        ->and($response->object())->toHaveCount(1);
});
