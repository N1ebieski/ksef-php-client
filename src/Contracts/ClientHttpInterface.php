<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

use N1ebieski\KSEFClient\ClientHttp\DTOs\RequestDTO;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;

interface ClientHttpInterface
{
    public function sendRequest(RequestDTO $requestDTO): ResponseInterface;
}
