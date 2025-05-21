<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\HttpClient;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;

interface HttpClientInterface
{
    public function sendRequest(Request $request): ResponseInterface;

    public function getSessionToken(): ?SessionToken;

    public function withSessionToken(SessionToken $sessionToken): self;
}
