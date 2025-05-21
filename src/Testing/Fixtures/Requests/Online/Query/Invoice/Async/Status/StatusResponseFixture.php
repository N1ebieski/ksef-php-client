<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Async\Status;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class StatusResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'processingCode' => 801,
        'processingDescription' => 'magn',
        'referenceNumber' => '20841003-T2-61EDCAEF30-90EE406BFB-A2',
        'elementReferenceNumber' => '20250521-EH-013965D417-02D2F20FE2-EA',
        'timestamp' => '2025-05-21T10:37:34.130Z',
        'partList' => [
            [
                'partReferenceNumber' => '20250521-ER-FC965E59A9-1D2E0FDF6A-54',
                'partName' => '5832908528_subject1_202505211724_202505211924_B_20250521T190312904',
                'partNumber' => 1,
                'partRangeFrom' => '2025-05-21T15:03:03.000Z',
                'partRangeTo' => '2025-05-21T17:03:03.000Z',
                'partExpiration' => '2025-06-10T17:03:08.498Z',
                'plainPartHash' => [
                    'hashSHA' => [
                        'algorithm' => 'SHA-256',
                        'encoding' => 'Base64',
                        'value' => '6bT7tDsUQ+9k132i8ECHV2xc+HRLFawrfATZg5ggAww=',
                    ],
                    'fileSize' => 1069,
                ],
            ],
        ]
    ];
}
