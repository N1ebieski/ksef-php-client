<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use Http\Discovery\Psr17Factory;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;
use Psr\Http\Client\ClientInterface;

final readonly class HttpClient implements HttpClientInterface
{
    public function __construct(
        private ClientInterface $client,
        private Config $config
    ) {
    }

    public function getSessionToken(): ?SessionToken
    {
        return $this->config->sessionToken;
    }

    public function withSessionToken(SessionToken $sessionToken): self
    {
        return new self($this->client, $this->config->withSessionToken($sessionToken));
    }

    public function sendRequest(Request $request): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $uri = $request->uri
            ->withBaseUrl($this->config->baseUri)
            ->withoutSlashAtEnd()
            ->withParameters($request->getParametersAsString());

        $clientRequest = $psr17Factory
            ->createRequest(
                method: $request->method->value,
                uri: $uri->value
            )
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        if ($this->config->sessionToken instanceof SessionToken) {
            $clientRequest = $clientRequest->withHeader('SessionToken', $this->config->sessionToken->value);
        }

        foreach ($request->headers as $name => $value) {
            $clientRequest = $clientRequest->withHeader($name, $value);
        }

        if ($request->method->hasBody()) {
            $body = $request->getBodyAsString();

            $clientRequest = $clientRequest->withBody(
                $psr17Factory->createStream($body)
            );
        }

        return new Response($this->client->sendRequest($clientRequest));
    }
}
