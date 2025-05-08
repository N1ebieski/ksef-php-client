<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Session\Requests\Responses;

use N1ebieski\KSEFClient\Testing\Fixtures\Resources\ResponseFixture;

final class AuthorisationChallengeResponseFixture extends ResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $contents = [
        'timestamp' => '2022-01-01T00:00:00+01:00',
        'challenge' => '1234567890',
    ];
}
