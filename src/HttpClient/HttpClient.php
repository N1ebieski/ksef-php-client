<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use Http\Discovery\Psr17Factory;
use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;
use Psr\Http\Client\ClientInterface;

final readonly class HttpClient implements HttpClientInterface
{
    public function __construct(
        private ClientInterface $client,
        private Config $config
    ) {
    }

    public function withConfig(Config $config): self
    {
        return new self($this->client, $config);
    }

    public function sendRequest(Request $request): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $uri = $request->uri->withBaseUrl($this->config->baseUri);

        if ($request->method->isEquals(Method::Get) && is_array($request->data)) {
            $uri = $uri->withParameters($request->data);
        }

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

        foreach ($request->headers as $header) {
            $clientRequest = $clientRequest->withHeader($header->name, $header->value);
        }

        if ($request->method->hasBody()) {
            $content = match (true) {
                is_string($request->data) => $request->data,
                is_array($request->data) => json_encode($request->data, JSON_THROW_ON_ERROR),
                default => ''
            };

            $clientRequest = $clientRequest->withBody($psr17Factory->createStream($content));
        }

        return new Response($this->client->sendRequest($clientRequest));
    }
}
