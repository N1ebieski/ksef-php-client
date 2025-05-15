<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\AuthorisationChallenge;

use N1ebieski\KSEFClient\Testing\Fixtures\AbstractFixture;

final class AuthorisationChallengeRequestFixture extends AbstractFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'nip' => '1111111111'
    ];
}
