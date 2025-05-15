<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;

interface HttpClientInterface
{
    public function sendRequest(Request $request): ResponseInterface;

    public function withSessionToken(SessionToken $sessionToken): self;
}
