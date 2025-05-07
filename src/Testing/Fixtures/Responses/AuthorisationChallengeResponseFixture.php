<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Responses;

use N1ebieski\KSEFClient\Testing\Fixtures\Fixture;

final class AuthorisationChallengeResponseFixture extends Fixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'timestamp' => '2022-01-01T00:00:00+01:00',
        'challenge' => '1234567890',
    ];

    public function __construct()
    {
    }
}
