<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class AuthorisationChallengeRequest extends AbstractRequest
{
    public function __construct(
        public NIP $nip
    ) {
    }
}
