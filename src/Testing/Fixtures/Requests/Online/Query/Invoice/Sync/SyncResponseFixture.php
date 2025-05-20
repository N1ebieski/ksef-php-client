<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Sync;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class SyncResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'timestamp' => '2025-05-20T10:06:42.968Z',
        'referenceNumber' => '20250520-SE-BB0BCE5FFC-C911B7C4F9-AC',
        'numberOfElements' => 506,
        'pageSize' => 10,
        'pageOffset' => 0,
        'invoiceHeaderList' => [
            [
                'invoiceReferenceNumber' => '1/05/2025',
                'ksefReferenceNumber' => '1111111111-20250520-34340AEE7B94-FA',
                'invoiceHash' => [
                    'hashSHA' => [
                        'algorithm' => 'SHA-256',
                        'encoding' => 'Base64',
                        'value' => 'yEWl4UCr3T+MAgJcawzeG1fuOAMHYe1Zkga0IhviDLg='
                    ],
                    'fileSize' => 2397
                ],
                'invoicingDate' => '2025-05-20',
                'acquisitionTimestamp' => '2025-05-20T10:06:00.820Z',
                'subjectBy' => [
                    'issuedByIdentifier' => [
                        'type' => 'onip',
                        'identifier' => '1111111111'
                    ],
                    'issuedByName' => [
                        'type' => 'fn',
                        'tradeName' => null,
                        'fullName' => 'Testowa Firma'
                    ]
                ],
                'subjectTo' => [
                    'issuedToIdentifier' => [
                        'type' => 'onip',
                        'identifier' => '5123957531'
                    ],
                    'issuedToName' => [
                        'type' => 'fn',
                        'tradeName' => null,
                        'fullName' => 'Firma'
                    ]
                ],
                'net' => '1667.61',
                'vat' => '383.38',
                'gross' => '2050.99',
                'currency' => 'PLN',
                'faP17Annotation' => false,
                'invoiceType' => 'VAT',
                'schemaVersion' => 'V2',
                'hidden' => false
            ]
        ]
    ];
}
