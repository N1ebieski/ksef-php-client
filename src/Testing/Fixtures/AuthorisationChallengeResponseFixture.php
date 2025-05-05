<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures;

final readonly class AuthorisationChallengeResponseFixture extends Fixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data;

    public function __construct()
    {
        $this->data = [
            'timestamp' => '2022-01-01T00:00:00+01:00',
            'challenge' => '1234567890',
        ];
    }
}
