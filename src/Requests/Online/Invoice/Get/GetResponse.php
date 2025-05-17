<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Get;

use Override;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\AbstractResponse;

final readonly class GetResponse extends AbstractResponse
{
    public function __construct(
        public string $contents
    ) {
    }

    #[Override]
    public static function fromResponse(ResponseInterface $response): static
    {
        return new self($response->body());
    }
}
