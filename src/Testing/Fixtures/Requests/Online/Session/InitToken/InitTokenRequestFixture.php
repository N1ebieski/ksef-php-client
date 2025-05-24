<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitToken;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class InitTokenRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'apiToken' => '1234567890',
        'initSessionToken' => [
            'challenge' => '20250516-CR-70912D5DEF-7402B64FF3-CD',
            'timestamp' => '2022-01-01T00:00:00+01:00',
            'identifier' => '1111111111'
        ]
    ];
}
