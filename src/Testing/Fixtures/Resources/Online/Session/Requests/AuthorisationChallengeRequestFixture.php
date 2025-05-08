<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Session\Requests;

use N1ebieski\KSEFClient\Testing\Fixtures\Fixture;

final class AuthorisationChallengeRequestFixture extends Fixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'nip' => '1111111111'
    ];
}
