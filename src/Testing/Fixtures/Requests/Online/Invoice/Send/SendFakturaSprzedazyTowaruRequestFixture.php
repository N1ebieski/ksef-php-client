<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

final class SendFakturaSprzedazyTowaruRequestFixture extends AbstractSendRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'faktura' => [
            'naglowek' => [
                'wariantFormularza' => 'FA (2)',
                'systemInfo' => 'KSEF-PHP-Client'
            ],
            'podmiot1' => [
                'daneIdentyfikacyjne' => [
                    'nip' => '1111111111',
                    'nazwa' => 'Testowa Firma'
                ],
                'adres' => [
                    'kodKraju' => 'PL',
                    'adresL1' => '30-549 Kraków',
                ]
            ],
            'podmiot2' => [
                'daneIdentyfikacyjne' => [
                    'idGroup' => [
                        'nip' => '5123957531'
                    ],
                    'nazwa' => 'Firma'
                ],
                'adres' => [
                    'kodKraju' => 'PL',
                    'adresL1' => 'Ulica 1/2, 11-111 Kraszawa',
                ]
            ],
            'fa' => [
                'kodWaluty' => 'PLN',
                'p_1' => '2025-05-11',
                'p_1M' => 'Warszawa',
                'p_2' => '1/05/2025',
                'p_6Group' => [
                    'p_6' => '2025-05-11'
                ],
                'p_13_1Group' => [
                    'p_13_1' => 1666.66,
                    'p_14_1' => 383.33,
                ],
                'p_13_3Group' => [
                    'p_13_3' => 0.95,
                    'p_14_3' => 0.05,
                ],
                'p_15' => 2050.99,
                'rodzajFaktury' => 'VAT',
                'faWiersz' => [
                    [
                        'nrWierszaFa' => 1,
                        'p_7' => 'lodówka Zimnotech mk1',
                        'p_8A' => 'szt',
                        'p_8B' => 1,
                        'p_9A' => 1626.01,
                        'p_11' => 1626.01,
                        'p_12' => '23'
                    ],
                    [
                        'nrWierszaFa' => 2,
                        'p_7' => 'wniesienie sprzętu',
                        'p_8A' => 'szt',
                        'p_8B' => 1,
                        'p_9A' => 40.65,
                        'p_11' => 40.65,
                        'p_12' => '23'
                    ],
                    [
                        'nrWierszaFa' => 3,
                        'p_7' => 'promocja lodówka pełna mleka',
                        'p_8A' => 'szt',
                        'p_8B' => 1,
                        'p_9A' => 0.95,
                        'p_11' => 0.95,
                        'p_12' => '5'
                    ]
                ],
                'platnosc' => [
                    'zaplataGroup' => [
                        'zaplacono' => '1',
                        'dataZaplaty' => '2022-01-27',
                    ],
                    'platnoscGroup' => [
                        'formaPlatnosci' => '6'
                    ]
                ]
            ]
        ]
    ];
}
