<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Responses\DTOs;

use N1ebieski\KSEFClient\ClientHttp\ValueObjects\SessionToken as SessionTokenValueObject;
use N1ebieski\KSEFClient\Support\DTO;
use SensitiveParameter;

final readonly class SessionToken extends DTO
{
    //@phpstan-ignore-next-line
    public function __construct(
        #[SensitiveParameter]
        public SessionTokenValueObject $token,
        public array $context
    ) {
    }
}
