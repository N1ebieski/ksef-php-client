<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Responses\Online\Invoice;

use N1ebieski\KSEFClient\Testing\Fixtures\Fixture;

final class SendResponseFixture extends Fixture
{
    /**
     * @var array<string, mixed>
     */
    public array $contents = [
        'timestamp' => '2022-01-01T00:00:00+01:00',
        'challenge' => '1234567890',
    ];
}
