<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Invoices;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class InvoicesResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'continuationToken' => 'continuationToken',
        'invoices' => [
            [
                'ksefNumber' => '1111111111-20260612-6310EC800000-DA',
                'collectiveIdentifierNumber' => '1111111111-IZ202607-65ED02180000-E7',
                'payment' => [
                    'amount' => 100,
                    'currency' => 'PLN',
                ],
                'description' => 'Opis faktury 1',
                'detailsHidden' => false,
            ],
        ],
    ];
}
