<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\HttpClient;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\HttpClient\Request;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\BaseUri;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;

interface HttpClientInterface
{
    public function sendRequest(Request $request): ResponseInterface;

    /**
     * @param array<int, Request> $requests
     * @return array<int|string, ResponseInterface|null>
     */
    public function sendAsyncRequest(array $requests): array;

    public function withBaseUri(BaseUri $baseUri): self;

    public function withAccessToken(AccessToken $accessToken): self;

    public function withoutAccessToken(): self;

    public function withEncryptionKey(EncryptionKey $encryptionKey): self;

    public function withEncryptedKey(EncryptedKey $encryptedKey): self;
}
