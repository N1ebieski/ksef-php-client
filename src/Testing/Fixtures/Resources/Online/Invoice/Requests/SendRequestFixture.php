<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Invoice\Requests;

use DateTimeImmutable;
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
                'nip' => '1111111111',
                'nazwa' => 'Testowa Firma'
            ],
            'adres' => [
                'kod_kraju' => 'PL',
                'adres_l1' => '30-549 Kraków',
            ]
        ],
        'podmiot2' => [
            'dane_identyfikacyjne' => [
                'idgroup' => [
                    'nip' => '5123957531'
                ],
                'nazwa' => 'Firma'
            ],
            'adres' => [
                'kod_kraju' => 'PL',
                'adres_l1' => 'Ulica 1/2, 11-111 Kraszawa',
            ]
        ],
        'fa' => [
            'kod_waluty' => 'PLN',
            'p_1' => '2025-05-11',
            'p_1m' => 'Warszawa',
            'p_2' => '1/05/2025',
            'p_6group' => [
                'p_6' => '2025-05-11'
            ],
            'p_13_1group' => [
                'p_13_1' => 1666.66,
                'p_14_1' => 383.33,
            ],
            'p_13_3group' => [
                'p_13_3' => 0.95,
                'p_14_3' => 0.05,
            ],
            'p_15' => 2050.99,
            'rodzaj_faktury' => 'VAT',
            'fa_wiersz' => [
                [
                    'nr_wiersza_fa' => 1,
                    'p_7' => 'lodówka Zimnotech mk1',
                    'p_8a' => 'szt',
                    'p_8b' => 1,
                    'p_9a' => 1626.01,
                    'p_11' => 1626.01,
                    'p_12' => '23'
                ],
                [
                    'nr_wiersza_fa' => 2,
                    'p_7' => 'wniesienie sprzętu',
                    'p_8a' => 'szt',
                    'p_8b' => 1,
                    'p_9a' => 40.65,
                    'p_11' => 40.65,
                    'p_12' => '23'
                ],
                [
                    'nr_wiersza_fa' => 3,
                    'p_7' => 'promocja lodówka pełna mleka',
                    'p_8a' => 'szt',
                    'p_8b' => 1,
                    'p_9a' => 0.95,
                    'p_11' => 0.95,
                    'p_12' => '5'
                ]
            ],
            'platnosc' => [
                'zaplata_group' => [
                    'zaplacono' => '1',
                    'data_zaplaty' => '2022-01-27',
                ],
                'platnosc_group' => [
                    'forma_platnosci' => '6'
                ]
            ]
        ]
    ];

    public function withTodayDate(): self
    {
        $todayDate = new DateTimeImmutable()->format('Y-m-d');

        $this->data['fa']['p_1'] = $todayDate; //@phpstan-ignore-line

        //@phpstan-ignore-next-line
        if (isset($this->data['fa']['p_6group']['p_6'])) {
            $this->data['fa']['p_6group']['p_6'] = $todayDate;
        }

        return $this;
    }
}
