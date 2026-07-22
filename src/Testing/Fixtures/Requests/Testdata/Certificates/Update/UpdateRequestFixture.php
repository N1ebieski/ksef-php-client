<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\Certificates\Update;

use N1ebieski\KSEFClient\Testing\Fixtures\AbstractFixture;

final class UpdateRequestFixture extends AbstractFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'serialNumber' => '0123456789ABCDEF',
        'validTo' => '2026-07-22T19:00:00+00:00',
    ];
}
