<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\DTOs\SessionToken;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\ReferenceNumber;
use N1ebieski\KSEFClient\Resources\Response;

final readonly class InitTokenResponse extends Response
{
    public function __construct(
        public DateTimeImmutable $timestamp,
        public ReferenceNumber $referenceNumber,
        public SessionToken $sessionToken
    ) {
    }
}
