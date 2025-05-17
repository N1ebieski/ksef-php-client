<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Get;

use N1ebieski\KSEFClient\Testing\Fixtures\AbstractFixture;

final class GetRequestFixture extends AbstractFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'ksef_reference_number' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD'
    ];
}
