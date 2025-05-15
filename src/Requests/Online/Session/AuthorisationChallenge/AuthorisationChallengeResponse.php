<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Requests\AbstractResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\Challenge;
use SensitiveParameter;

final readonly class AuthorisationChallengeResponse extends AbstractResponse
{
    public function __construct(
        #[SensitiveParameter]
        public DateTimeImmutable $timestamp,
        #[SensitiveParameter]
        public Challenge $challenge,
    ) {
    }
}
