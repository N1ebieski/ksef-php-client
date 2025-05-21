<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Async\Status;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class StatusRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'queryElementReferenceNumber' => '20250521-EH-013965D417-02D2F20FE2-EA',
    ];
}
