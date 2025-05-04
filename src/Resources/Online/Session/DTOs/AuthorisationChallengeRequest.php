<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\DTOs;

use N1ebieski\KSEFClient\Resources\Request;
use N1ebieski\KSEFClient\ValueObjects\Nip;

final readonly class AuthorisationChallengeRequest extends Request
{
    public function __construct(
        public Nip $nip
    ) {
    }
}
