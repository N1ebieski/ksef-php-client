<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\DTOs;

use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken as SessionTokenValueObject;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use SensitiveParameter;

final readonly class SessionToken extends AbstractDTO
{
    //@phpstan-ignore-next-line
    public function __construct(
        #[SensitiveParameter]
        public SessionTokenValueObject $token,
        public array $context
    ) {
    }
}
