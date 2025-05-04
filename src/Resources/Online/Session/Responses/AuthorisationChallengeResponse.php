<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Responses;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Resources\Online\Session\ValueObjects\Challenge;
use N1ebieski\KSEFClient\Resources\Response;

final readonly class AuthorisationChallengeResponse extends Response
{
    public function __construct(
        public DateTimeImmutable $timestamp,
        public Challenge $challenge,
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            timestamp: new DateTimeImmutable($data['timestamp']),
            challenge: new Challenge($data['challenge'])
        );
    }
}
