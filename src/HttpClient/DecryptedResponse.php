<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;
use Psr\Http\Message\ResponseInterface as MessageResponseInterface;

final readonly class DecryptedResponse implements ResponseInterface
{
    public MessageResponseInterface $baseResponse;

    public function __construct(
        private ResponseInterface $response,
        private string $decryptedBody
    ) {
        $this->baseResponse = $response->baseResponse;
    }

    public function status(): int
    {
        return $this->response->status();
    }

    public function json(): array
    {
        return $this->response->json();
    }

    public function object(): object
    {
        return $this->response->object();
    }

    public function body(): string
    {
        return $this->decryptedBody;
    }

    public function toArray(KeyType $keyType = KeyType::Camel): array
    {
        return $this->response->toArray($keyType);
    }
}
