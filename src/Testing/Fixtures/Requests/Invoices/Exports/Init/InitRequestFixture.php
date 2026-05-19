<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Invoices\Exports\Init;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class InitRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'filters' => [
            'subjectType' => 'Subject1',
            'dateRange' => [
                'dateType' => 'Issue',
                'from' => '2019-08-24T14:15:22Z',
                'to' => '2019-08-24T14:15:22Z',
            ],
            'ksefNumber' => '5265877635-20250626-010080DD2B5E-26',
            'invoiceNumber' => 'FA/XPWIC-7900685789/06/2025',
            'amount' => [
                'type' => 'Brutto',
                'from' => 0.1,
                'to' => 0.1,
            ],
            'sellerNip' => '1111111111',
            'buyerIdentifier' => [
                'type' => 'None',
                'value' => 'string',
            ],
            'currencyCodes' => [
                'AED',
            ],
            'invoicingMode' => 'Online',
            'isSelfInvoicing' => true,
            'formType' => 'FA',
            'invoiceTypes' => [
                'Vat',
            ],
            'hasAttachment' => true,
        ],
        'compressionType' => 'TarGz',
    ];

    public function withSubjectType(string $subjectType): self
    {
        $this->data['filters']['subjectType'] = $subjectType;

        return $this;
    }

    public function withDateRange(string $range): self
    {
        $now = new DateTimeImmutable('now');

        $this->data['filters']['dateRange']['from'] = $now->modify($range)->format('Y-m-d\TH:i:s');
        $this->data['filters']['dateRange']['to'] = $now->format('Y-m-d\TH:i:s');

        return $this;
    }
}
