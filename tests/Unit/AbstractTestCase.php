<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit;

use Mockery;
use Mockery\MockInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\ClientResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Exceptions\ExceptionHandler;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\HttpClient\Response;
use N1ebieski\KSEFClient\Resources\ClientResource;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\BaseUri;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\SimpleCache\CacheInterface;

abstract class AbstractTestCase extends TestCase
{
    public function createResponseStubWithFixture(AbstractResponseFixture $responseFixture): MockInterface & ResponseInterface
    {
        $streamStub = Mockery::mock(StreamInterface::class);
        $streamStub->shouldReceive('getContents')->andReturn($responseFixture->toContents());

        $responseStub = Mockery::mock(ResponseInterface::class);
        $responseStub->shouldReceive('getStatusCode')->andReturn($responseFixture->statusCode);
        $responseStub->shouldReceive('getBody')->andReturn($streamStub);
        $responseStub->shouldReceive('getHeaders')->andReturn([]);

        /** @var MockInterface&ResponseInterface */
        return $responseStub;
    }

    public function createHttpClientStubWithFixture(AbstractResponseFixture $responseFixture): MockInterface & HttpClientInterface
    {
        $httpClientStub = Mockery::mock(HttpClientInterface::class);
        $httpClientStub->shouldReceive('withBaseUri')->andReturnSelf();
        $httpClientStub->shouldReceive('withAccessToken')->andReturnSelf();
        $httpClientStub->shouldReceive('withoutAccessToken')->andReturnSelf();
        $httpClientStub->shouldReceive('withEncryptedKey')->andReturnSelf();

        /** @var MockInterface&ResponseInterface $responseStub */
        $responseStub = $this->createResponseStubWithFixture($responseFixture);

        $response = new Response($responseStub);
        $response->throwExceptionIfError();

        $httpClientStub->shouldReceive('sendRequest')->andReturn($response);

        /** @var MockInterface&HttpClientInterface */
        return $httpClientStub;
    }

    public function createClientStubWithFixture(
        AbstractResponseFixture $responseFixture,
        ?CacheInterface $cacheStub = null
    ): ClientResourceInterface {
        $httpClientStub = $this->createHttpClientStubWithFixture($responseFixture);

        /** @var MockInterface&HttpClientInterface $httpClientStub */
        return $this->createClientStub(
            httpClientStub: $httpClientStub,
            cacheStub: $cacheStub
        );
    }

    public function createClientStub(
        HttpClientInterface $httpClientStub,
        ?CacheInterface $cacheStub = null
    ): ClientResourceInterface {
        /** @var MockInterface&HttpClientInterface $httpClientStub */
        return new ClientResource(
            client: $httpClientStub,
            config: new Config(
                mode: Mode::Test,
                baseUri: new BaseUri(Mode::Test->getApiUrl()->value),
                latarniaBaseUri: new BaseUri(Mode::Test->getLatarniaApiUrl()->value),
                encryptionKey: EncryptionKeyFactory::makeRandom()
            ),
            exceptionHandler: new ExceptionHandler(),
            cache: $cacheStub,
        );
    }
}
