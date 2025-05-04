<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Responses\DTOs;

use N1ebieski\KSEFClient\ClientHttp\ValueObjects\SessionToken as SessionTokenValueObject;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class SessionToken extends DTO
{
    public function __construct(
        public SessionTokenValueObject $token,
        public array $context
    ) {
    }
}
