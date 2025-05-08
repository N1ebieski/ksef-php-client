<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Invoice\Requests;

use N1ebieski\KSEFClient\Testing\Fixtures\Fixture;

final class SendRequestFixture extends Fixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'naglowek' => [
            'wariant_formularza' => 'FA (2)',
            'system_info' => 'KSEF-PHP-Client'
        ],
        'podmiot1' => [
            'dane_identyfikacyjne' => [
                'nip' => '9999999999',
                'nazwa' => 'ABC AGD sp. z o. o.'
            ],
            'adres' => [
                'kod_kraju' => 'PL',
                'adres_l1' => 'ul. Kwiatowa 1 m. 2',
                'adres_l2' => '00-001 Warszawa'
            ]
        ],
        'podmiot2' => [
            'dane_identyfikacyjne' => [
                'nip' => '1111111111',
                'nazwa' => 'F.H.U. Jan Kowalski'
            ],
            'adres' => [
                'kod_kraju' => 'PL',
                'adres_l1' => 'ul. Polna 1',
                'adres_l2' => '00-001 Warszawa'
            ]
        ],
        'fa' => [
            'kod_waluty' => 'PLN',
            'p_1' => '2022-02-15',
            'p_1m' => 'Warszawa',
            'p_2' => 'FV2022/02/150',
            'p_6' => '2022-01-27',
            'p_13_1' => 1666.66,
            'p_14_1' => 383.33,
            'p_13_3' => 0.95,
            'p_14_3' => 0.05,
            'p_15' => 2051,
            'rodzaj_faktury' => 'VAT',
            'fa_wiersz' => [
                [
                    'nr_wiersza_fa' => 1,
                    'p_7' => 'lodówka Zimnotech mk1',
                    'p_8A' => 'szt.',
                    'p_8B' => 1,
                    'p_9A' => 1626.01,
                    'p_11' => 1626.01,
                    'p_12' => '23'
                ],
                [
                    'nr_wiersza_fa' => 2,
                    'p_7' => 'wniesienie sprzętu',
                    'p_8A' => 'szt.',
                    'p_8B' => 1,
                    'p_9A' => 40.65,
                    'p_11' => 40.65,
                    'p_12' => '23'
                ],
                [
                    'nr_wiersza_fa' => 3,
                    'p_7' => 'promocja lodówka pełna mleka',
                    'p_8A' => 'szt.',
                    'p_8B' => 1,
                    'p_9A' => 0.95,
                    'p_11' => 0.95,
                    'p_12' => '5'
                ]
            ],
            'platnosc' => [
                'zaplacono' => '1',
                'data_zaplaty' => '2022-01-27',
                'forma_platnosci' => '6'
            ]
        ]
    ];
}
