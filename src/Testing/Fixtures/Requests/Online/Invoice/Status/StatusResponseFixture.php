<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Status;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class StatusResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'elementReferenceNumber' => '20841003-T2-61EDCAEF30-90EE406BFB-A2',
        'processingCode' => 801,
        'processingDescription' => 'magn',
        'referenceNumber' => '20471001-XF-0BE2B1209D-637CF97305-04',
        'timestamp' => '2022-01-01T00:00:00+01:00',
        'invoiceStatus' => [
            'invoiceNumber' => 'INV_1111111111_2222222222_4727711098527000',
            'ksefReferenceNumber' => '1111111111-20211001-FFFFFF-FFFFFF-FF',
            'acquisitionTimestamp' => '2021-10-01T12:13:14.999Z'
        ]
    ];

    public function withInvoiceStatus(): self
    {
        $this->data['invoiceStatus'] = [
            'invoiceNumber' => 'INV_1111111111_2222222222_4727711098527000',
            'ksefReferenceNumber' => '1111111111-20211001-FFFFFF-FFFFFF-FF',
            'acquisitionTimestamp' => '2021-10-01T12:13:14.999Z'
        ];

        return $this;
    }

    public function withEmptyInvoiceStatus(): self
    {
        $this->data['invoiceStatus'] = [];

        return $this;
    }
}
