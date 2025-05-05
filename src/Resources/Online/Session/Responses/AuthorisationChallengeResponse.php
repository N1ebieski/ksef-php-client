<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Responses;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Resources\Online\Session\ValueObjects\Challenge;
use N1ebieski\KSEFClient\Resources\Response;
use SensitiveParameter;

final readonly class AuthorisationChallengeResponse extends Response
{
    public function __construct(
        #[SensitiveParameter]
        public DateTimeImmutable $timestamp,
        #[SensitiveParameter]
        public Challenge $challenge,
    ) {
    }
}
