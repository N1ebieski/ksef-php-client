<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Async\Fetch;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class FetchRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'queryElementReferenceNumber' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD',
        'partElementReferenceNumber' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD',
    ];
}
