<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\DTOs;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\DTOs\SubjectBy;
use N1ebieski\KSEFClient\Requests\DTOs\SubjectTo;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KsefReferenceNumber;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AcquisitionTimestampThresholdFrom;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AcquisitionTimestampThresholdTo;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AmountFrom;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AmountTo;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\AmountType;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\CurrencyCode;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\InvoiceNumber;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\InvoiceType;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\InvoicingDateFrom;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\InvoicingDateTo;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\QueryCriteriaInvoiceType;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects\SchemaType;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class QueryCriteriaInvoiceDetailGroup extends AbstractDTO implements BodyInterface
{
    use HasToBody;

    public QueryCriteriaInvoiceType $type;

    /**
     * @param Optional|array<int, CurrencyCode> $currencyCodes
     * @param Optional|array<int, InvoiceType> $invoiceTypes
     */
    public function __construct(
        public InvoicingDateFrom $invoicingDateFrom,
        public InvoicingDateTo $invoicingDateTo,
        public Optional | AmountFrom $amountFrom = new Optional(),
        public Optional | AmountTo $amountTo = new Optional(),
        public Optional | AmountType $amountType = new Optional(),
        public Optional | array $currencyCodes = new Optional(),
        public Optional | bool $faP17Annotation = new Optional(),
        public Optional | InvoiceNumber $invoiceNumber = new Optional(),
        public Optional | array $invoiceTypes = new Optional(),
        public Optional | KsefReferenceNumber $ksefReferenceNumber = new Optional(),
        public Optional | SchemaType $schemaType = new Optional(),
        public Optional | SubjectBy $subjectBy = new Optional(),
        public Optional | SubjectTo $subjectTo = new Optional(),
    ) {
        $this->type = QueryCriteriaInvoiceType::Detail;
    }
}
