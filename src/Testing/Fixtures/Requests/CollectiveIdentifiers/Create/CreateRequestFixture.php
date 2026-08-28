<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Create;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class CreateRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'invoices' => [
            [
                'ksefNumber' => '1111111111-20260612-6310EC800000-DA',
                'payment' => [
                    'amount' => 100,
                    'currency' => 'PLN',
                ],
                'description' => 'Opis faktury 1',
            ],
            [
                'ksefNumber' => '1111111111-20260612-62EAFD400000-B3',
                'payment' => [
                    'amount' => 45,
                    'currency' => 'PLN',
                ],
                'description' => 'Opis faktury 2',
            ],
        ],
    ];
}
