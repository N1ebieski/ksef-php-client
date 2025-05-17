<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;

interface FromResponseInterface
{
    public static function fromResponse(ResponseInterface $response): self;
}
