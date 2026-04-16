<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\RateLimits\Limits;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class LimitsRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'rateLimits' => [
            'onlineSession' => [
                'perSecond' => 100,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'batchSession' => [
                'perSecond' => 100,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'invoiceSend' => [
                'perSecond' => 100,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'invoiceStatus' => [
                'perSecond' => 100,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'sessionList' => [
                'perSecond' => 50,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'sessionInvoiceList' => [
                'perSecond' => 100,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'sessionMisc' => [
                'perSecond' => 100,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'invoiceMetadata' => [
                'perSecond' => 50,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'invoiceExport' => [
                'perSecond' => 50,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'invoiceExportStatus' => [
                'perSecond' => 100,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'invoiceDownload' => [
                'perSecond' => 50,
                'perMinute' => 100,
                'perHour' => 100,
            ],
            'other' => [
                'perSecond' => 100,
                'perMinute' => 100,
                'perHour' => 100,
            ],
        ],
    ];
}
