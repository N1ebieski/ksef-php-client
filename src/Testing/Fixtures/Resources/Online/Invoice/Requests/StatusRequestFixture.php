<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Invoice\Requests;

use N1ebieski\KSEFClient\Testing\Fixtures\Fixture;

final class StatusRequestFixture extends Fixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'invoice_element_reference_number' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD'
    ];
}
