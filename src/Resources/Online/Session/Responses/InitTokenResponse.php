<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Responses;

use N1ebieski\KSEFClient\ClientHttp\ValueObjects\SessionToken;
use N1ebieski\KSEFClient\Resources\Response;

final readonly class InitTokenResponse extends Response
{
    public function __construct(
        public SessionToken $sessionToken
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(new SessionToken($data['sessionToken']['token']));
    }
}
