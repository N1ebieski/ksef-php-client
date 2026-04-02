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
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'batchSession' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'invoiceSend' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'invoiceStatus' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'sessionList' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'sessionInvoiceList' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'sessionMisc' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'invoiceMetadata' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'invoiceExport' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'invoiceDownload' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
            'other' => [
                'perSecond' => 10,
                'perMinute' => 10,
                'perHour' => 100,
            ],
        ],
    ];
}
