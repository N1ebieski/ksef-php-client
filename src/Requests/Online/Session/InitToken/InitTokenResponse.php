<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitToken;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Requests\AbstractResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\DTOs\SessionToken;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\ReferenceNumber;

final readonly class InitTokenResponse extends AbstractResponse
{
    public function __construct(
        public DateTimeImmutable $timestamp,
        public ReferenceNumber $referenceNumber,
        public SessionToken $sessionToken
    ) {
    }
}
