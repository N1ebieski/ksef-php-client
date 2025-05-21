<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Async\Init;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class InitResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 202;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'processingCode' => 801,
        'processingDescription' => 'magn',
        'referenceNumber' => '20841003-T2-61EDCAEF30-90EE406BFB-A2',
        'elementReferenceNumber' => '20250521-EH-013965D417-02D2F20FE2-EA',
        'timestamp' => '2022-01-01T00:00:00+01:00',
    ];
}
