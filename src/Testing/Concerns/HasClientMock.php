<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Concerns;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\RootResourceInterface;
use N1ebieski\KSEFClient\HttpClient\Response;
use N1ebieski\KSEFClient\Resources\RootResource;
use N1ebieski\KSEFClient\Testing\Fixtures\Fixture;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

trait HasClientMock
{
    public function getClientStub(Fixture $response): RootResourceInterface
    {
        /** @var TestCase $this */
        //@phpstan-ignore-next-line
        $streamStub = $this->createStub(StreamInterface::class);
        $streamStub->method('getContents')->willReturn(json_encode($response->contents));

        $responseStub = $this->createStub(ResponseInterface::class);
        $responseStub->method('getStatusCode')->willReturn($response->statusCode);
        $responseStub->method('getBody')->willReturn($streamStub);

        $httpClientStub = $this->createStub(HttpClientInterface::class);
        $httpClientStub->method('sendRequest')->willReturn(new Response($responseStub));

        return new RootResource($httpClientStub);
    }
}
