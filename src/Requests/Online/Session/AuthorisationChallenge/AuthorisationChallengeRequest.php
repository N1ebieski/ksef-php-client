<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\DTOs\SubjectIdentifierBy;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;

final readonly class AuthorisationChallengeRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public SubjectIdentifierBy $contextIdentifier
    ) {
    }
}
