<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ClientHttp;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        public BaseResponseInterface $baseResponse
    ) {
    }

    public function json(): array
    {
        /** @var array<string, mixed> */
        return json_decode($this->baseResponse->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);
    }
}
