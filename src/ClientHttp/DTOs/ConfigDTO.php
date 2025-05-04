<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ClientHttp\DTOs;

use N1ebieski\KSEFClient\ClientHttp\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\SessionToken;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class ConfigDTO extends DTO
{
    public function __construct(
        public BaseUri $baseUri,
        public ?SessionToken $sessionToken = null
    ) {
    }

    public function withSessionToken(SessionToken $sessionToken): self
    {
        return new self($this->baseUri, $sessionToken);
    }
}
