<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Async\Init;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class InitRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'queryCriteria' => [
            'subjectType' => 'subject2',
        ],
    ];

    public function withDetail(): self
    {
        $now = new DateTimeImmutable('now');

        $this->data['queryCriteria'] = [
            ...$this->data['queryCriteria'], //@phpstan-ignore-line
            'queryCriteriagroup' => [
                'invoicingDateFrom' => $now->modify('-2 weeks')->format('Y-m-d\TH:i:s'),
                'invoicingDateTo' => $now->format('Y-m-d\TH:i:s'),
                'amountFrom' => 0.32,
                'amountTo' => 1.43,
                'amountType' => 'brutto',
                'currencyCodes' => [
                    'PLN',
                    'EUR'
                ],
                'faP17Annotation' => true,
                'invoiceNumber' => 'ABC',
                'invoiceTypes' => [
                    'VAT',
                    'KOR'
                ],
                // 'ksefReferenceNumber' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD',
                // 'schemaType' => 'VAT_RR',
                'subjectBy' => [
                    'issuedByIdentifier' => [
                        'subjectIdentifierBygroup' => [
                            'subjectIdentifierByCompany' => '1111111111'
                        ]
                    ],
                    'issuedByName' => [
                        'subjectNamegroup' => [
                            'subjectPersonName' => [
                                'firstName' => 'Jan',
                                'surname' => 'Kowalski'
                            ]
                        ]
                    ]
                ],
                'subjectTo' => [
                    'issuedToIdentifier' => [
                        'subjectIdentifierTogroup' => [
                            'subjectIdentifierToCompany' => '1111111111'
                        ]
                    ],
                    'issuedToName' => [
                        'subjectNamegroup' => []
                    ]
                ]
            ]
        ];

        return $this;
    }

    public function withSubjectType(string $subjectType): self
    {
        $this->data['queryCriteria']['subjectType'] = $subjectType;

        return $this;
    }

    public function withRange(string $range): self
    {
        $now = new DateTimeImmutable('now');

        $this->data['queryCriteria'] = [
            ...$this->data['queryCriteria'], //@phpstan-ignore-line
            'queryCriteriagroup' => [
                'invoicingDateFrom' => $now->modify($range)->format('Y-m-d\TH:i:s'),
                'invoicingDateTo' => $now->format('Y-m-d\TH:i:s')
            ]
        ];

        return $this;
    }
}
