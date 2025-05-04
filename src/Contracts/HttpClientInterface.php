<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;

interface HttpClientInterface
{
    public function sendRequest(Request $request): ResponseInterface;
}
