<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Testdata\RateLimits;

use N1ebieski\KSEFClient\Support\AbstractDTO;

final class RateLimits extends AbstractDTO
{
    public function __construct(
        public readonly Limits $onlineSession,
        public readonly Limits $batchSession,
        public readonly Limits $invoiceSend,
        public readonly Limits $invoiceStatus,
        public readonly Limits $sessionList,
        public readonly Limits $sessionInvoiceList,
        public readonly Limits $sessionMisc,
        public readonly Limits $invoiceMetadata,
        public readonly Limits $invoiceExport,
        public readonly Limits $invoiceExportStatus,
        public readonly Limits $invoiceDownload,
        public readonly Limits $other
    ) {
    }
}
