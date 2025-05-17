<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\Terminate;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class TerminateResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'timestamp' => '2022-01-01T00:00:00+01:00',
        'processingCode' => 801,
        'processingDescription' => 'magn',
        'referenceNumber' => '20471001-XF-0BE2B1209D-637CF97305-04',
    ];
}
