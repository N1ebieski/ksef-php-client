<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ClientHttp;

use Http\Discovery\Psr17Factory;
use N1ebieski\KSEFClient\ClientHttp\DTOs\ConfigDTO;
use N1ebieski\KSEFClient\ClientHttp\DTOs\RequestDTO;
use N1ebieski\KSEFClient\Contracts\ClientHttpInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final readonly class ClientHttp implements ClientHttpInterface
{
    public function __construct(
        private ClientInterface $client,
        private ConfigDTO $configDTO
    ) {
    }

    public function sendRequest(RequestDTO $requestDTO): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $request = $psr17Factory
            ->createRequest(
                method: $requestDTO->method->value,
                uri: $requestDTO->uri->withBaseUrl($this->configDTO->baseUri)->value
            )
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        if ($this->configDTO->sessionToken !== null) {
            $request = $request->withHeader('SessionToken', $this->configDTO->sessionToken->value);
        }

        foreach ($requestDTO->headers as $header) {
            $request = $request->withHeader($header->name, $header->value);
        }

        if ($requestDTO->method->hasBody()) {
            $content = match (true) {
                is_string($requestDTO->data) => $requestDTO->data,
                is_array($requestDTO->data) => json_encode($requestDTO->data, JSON_THROW_ON_ERROR),
                default => ''
            };

            $request = $request->withBody($psr17Factory->createStream($content));
        }

        return $this->client->sendRequest($request);
    }
}
