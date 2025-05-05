<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Responses;

use N1ebieski\KSEFClient\Resources\Online\Session\Responses\DTOs\SessionToken;
use N1ebieski\KSEFClient\Resources\Response;

final readonly class InitTokenResponse extends Response
{
    public function __construct(
        public SessionToken $sessionToken
    ) {
    }
}
