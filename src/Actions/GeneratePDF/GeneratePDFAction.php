<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\GeneratePDF;

use DateTimeInterface;
use N1ebieski\KSEFClient\Actions\AbstractAction;
use N1ebieski\KSEFClient\DTOs\QRCodes;
use N1ebieski\KSEFClient\ValueObjects\KsefFeInvoiceConverterPath;
use N1ebieski\KSEFClient\ValueObjects\Requests\KsefNumber;

final class GeneratePDFAction extends AbstractAction
{
    public function __construct(
        public readonly KsefFeInvoiceConverterPath $ksefFeInvoiceConverterPath,
        public readonly string $nodePath = 'node',
        public readonly ?string $invoiceDocument = null,
        public readonly ?string $upoDocument = null,
        public readonly ?string $confirmationDocument = null,
        public readonly ?KsefNumber $ksefNumber = null,
        public readonly ?DateTimeInterface $acquisitionDate = null,
        public readonly ?QRCodes $qrCodes = null,
        public readonly ?string $watermark = null,
    ) {
    }
}
