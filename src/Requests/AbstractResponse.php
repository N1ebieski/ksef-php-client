<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests;

use N1ebieski\KSEFClient\Contracts\FromResponseInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;

abstract readonly class AbstractResponse extends AbstractDTO implements FromResponseInterface
{
    public static function fromResponse(ResponseInterface $response): static
    {
        return self::from($response->json());
    }
}
