<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitToken;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class InitTokenResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 201;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'timestamp' => '2025-05-16T12:34:42.540Z',
        'referenceNumber' => '20250516-SE-F2D9E68F0A-519AAC34D6-19',
        'sessionToken' => [
            'token' => '6b790ceffabce9348a56279ecfb0fb2d52a28552dc952660d932b7df05e50ca2',
        ]
    ];
}
