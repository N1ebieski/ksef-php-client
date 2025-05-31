<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use Http\Discovery\Psr17Factory;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Exception\ExceptionHandler;
use N1ebieski\KSEFClient\HttpClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

final readonly class HttpClient implements HttpClientInterface
{
    public function __construct(
        private ClientInterface $client,
        private Config $config,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function getSessionToken(): ?SessionToken
    {
        return $this->config->sessionToken;
    }

    public function withSessionToken(SessionToken $sessionToken): self
    {
        return new self(
            client: $this->client,
            config: $this->config->withSessionToken($sessionToken),
            logger: $this->logger
        );
    }

    public function sendRequest(Request $request): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $request = $request->withUri($request->uri->withBaseUrl($this->config->baseUri)->withoutSlashAtEnd());

        if ($this->config->sessionToken instanceof SessionToken) {
            $request = $request->withHeader('SessionToken', $this->config->sessionToken->value);
        }

        $clientRequest = $psr17Factory->createRequest(
            method: $request->method->value,
            uri: $request->uri->withParameters($request->getParametersAsString())->value
        );

        foreach ($request->headers as $name => $value) {
            $clientRequest = $clientRequest->withHeader($name, $value);
        }

        if ($request->method->hasBody()) {
            $body = $request->getBodyAsString();

            $clientRequest = $clientRequest->withBody(
                $psr17Factory->createStream($body)
            );
        }

        if ($this->logger instanceof LoggerInterface) {
            $this->logger->debug('Sending request to KSEF', $request->toArray());
        }

        $response = new Response(
            baseResponse: $this->client->sendRequest($clientRequest),
            exceptionHandler: new ExceptionHandler($this->logger)
        );

        if ($this->logger instanceof LoggerInterface) {
            $this->logger->debug('Received response from KSEF', $response->toArray());
        }

        return $response;
    }
}
