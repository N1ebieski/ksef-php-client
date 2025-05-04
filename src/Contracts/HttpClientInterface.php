<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\RequestDTO;

interface HttpClientInterface
{
    public function sendRequest(RequestDTO $requestDTO): ResponseInterface;
}
