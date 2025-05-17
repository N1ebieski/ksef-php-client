<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\Status\Status;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class StatusResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'timestamp' => '2022-01-01T00:00:00+01:00',
        'referenceNumber' => '20841003-T2-61EDCAEF30-90EE406BFB-A2',
        'numberOfElements' => 0,
        'pageSize' => 20,
        'pageOffset' => 0,
        'processingCode' => 801,
        'processingDescription' => 'magn',
        'invoiceStatusList' => [
            [
                'processingCode' => 801,
                'processingDescription' => 'magn',
                'elementReferenceNumber' => '1111111111-20211001-FFFFFF-FFFFFF-FF',
                'invoiceNumber' => '1/05/2025',
                'ksefReferenceNumber' => '1111111111-20211001-FFFFFF-FFFFFF-FF',
                'acquisitionTimestamp' => '2021-10-01T12:13:14.999Z'
            ]
        ],
        'creationTimestamp' => '2022-01-01T00:00:00+01:00',
        'lastUpdateTimestamp' => '2022-01-01T00:00:00+01:00',
        'entityType' => '02',
    ];

    public function withInvoiceStatusList(): self
    {
        $this->data['invoiceStatusList'] = [
            [
                'processingCode' => 801,
                'processingDescription' => 'magn',
                'elementReferenceNumber' => '1111111111-20211001-FFFFFF-FFFFFF-FF',
                'invoiceNumber' => '1/05/2025',
                'ksefReferenceNumber' => '1111111111-20211001-FFFFFF-FFFFFF-FF',
                'acquisitionTimestamp' => '2021-10-01T12:13:14.999Z'
            ]
        ];

        return $this;
    }

    public function withoutInvoiceStatusList(): self
    {
        unset($this->data['invoiceStatusList']);

        return $this;
    }
}
