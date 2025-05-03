<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Responses;

use DateTimeImmutable;

final readonly class AuthorisationChallengeResponse
{
    public function __construct(
        public DateTimeImmutable $timestamp,
        public string $challenge,
    ) {
    }

    public static function from(array $data): self
    {
        return new self(
            timestamp: new DateTimeImmutable($data['timestamp']),
            challenge: $data['challenge']
        );
    }
}
