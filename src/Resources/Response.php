<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources;

use N1ebieski\KSEFClient\Contracts\FromResponseInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Support\DTO;

abstract readonly class Response extends DTO implements FromResponseInterface
{
    public static function fromResponse(ResponseInterface $response): static
    {
        return self::from($response->json());
    }
}
