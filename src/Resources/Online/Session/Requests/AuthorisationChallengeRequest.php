<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Requests;

use N1ebieski\KSEFClient\Resources\Request;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class AuthorisationChallengeRequest extends Request
{
    public function __construct(
        public NIP $nip
    ) {
    }
}
