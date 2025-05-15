<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\DTOs;

use N1ebieski\KSEFClient\HttpClient\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use SensitiveParameter;

final readonly class Config extends AbstractDTO
{
    public function __construct(
        public BaseUri $baseUri,
        #[SensitiveParameter]
        public ?SessionToken $sessionToken = null
    ) {
    }

    public function withSessionToken(SessionToken $sessionToken): self
    {
        return new self($this->baseUri, $sessionToken);
    }
}
