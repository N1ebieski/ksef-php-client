<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

final class SendFakturaSprzedazyUslugLeasinguOperacyjnegoRequestFixture extends AbstractSendRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
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
                'adresL1' => 'ul. Kwiatowa 1 m. 2',
                'adresL2' => '00-001 Warszawa'
            ],
            'daneKontaktowe' => [
                [
                    'email' => 'abc@abc.pl',
                    'telefon' => '667444555'
                ]
            ]
        ],
        'podmiot2' => [
            'daneIdentyfikacyjne' => [
                'idGroup' => [
                    'nip' => '5123957531'
                ],
                'nazwa' => 'Gmina Bzdziszewo'
            ],
            'adres' => [
                'kodKraju' => 'PL',
                'adresL1' => 'Bzdziszewo 1',
                'adresL2' => '00-007 Bzdziszewo'
            ],
            'daneKontaktowe' => [
                [
                    'email' => 'bzdziszewo@tuwartoinwestowac.pl',
                    'telefon' => '555777999'
                ]
            ]
        ],
        'podmiot3' => [
            'daneIdentyfikacyjne' => [
                'idGroup' => [
                    'nip' => '2222222222'
                ],
                'nazwa' => 'Szkoła Podstawowa w Bzdziszewie'
            ],
            'adres' => [
                'kodKraju' => 'PL',
                'adresL1' => 'ul. Akacjowa 200',
                'adresL2' => '00-007 Bzdziszewo'
            ],
            'daneKontaktowe' => [
                [
                    'email' => 'sp@bzdziszewo.pl',
                    'telefon' => '666888999'
                ]
            ],
            'rolaGroup' => [
                'rola' => '8'
            ]
        ],
        'fa' => [
            'kodWaluty' => 'PLN',
            'p_1' => '2025-05-11',
            'p_1M' => 'Warszawa',
            'p_2' => 'FV2022/02/150',
            'p_6Group' => [
                'okresFa' => [
                    'p_6_Od' => '2022-01-01',
                    'p_6_Do' => '2022-12-31'
                ]
            ],
            'p_13_1Group' => [
                'p_13_1' => 2000,
                'p_14_1' => 460,
            ],
            'p_13_7' => 300,
            'p_15' => 2760,
            'adnotacje' => [
                'zwolnienie' => [
                    'p_19Group' => [
                        'p_19ABCGroup' => [
                            'p_19A' => 'art. 43 ust. 1 pkt 37 ustawy VAT'
                        ]
                    ]
                ],
                'pMarzy' => [
                    'p_PMarzyGroup' => [
                        'p_PMarzy_2_3Group' => [
                            'p_PMarzy_3_1' => '1'
                        ]
                    ]
                ]
            ],
            'rodzajFaktury' => 'VAT',
            'dodatkowyOpis' => [
                [
                    'klucz' => 'część odsetkowa raty',
                    'wartosc' => 'netto 200, vat 46'
                ]
            ],
            'faWiersz' => [
                [
                    'nrWierszaFa' => 1,
                    'uu_id' => 'aaaa111133339990',
                    'p_7' => 'rata leasingowa za 01/2022',
                    'p_8A' => 'szt.',
                    'p_8B' => 1,
                    'p_9A' => 2000,
                    'p_11' => 2000,
                    'p_12' => '23'
                ],
                [
                    'nrWierszaFa' => 2,
                    'uu_id' => 'aaaa111133339991',
                    'p_7' => 'pakiet ubezpieczeń za 01/2022',
                    'p_8A' => 'szt.',
                    'p_8B' => 1,
                    'p_9A' => 300,
                    'p_11' => 300,
                    'p_12' => 'zw'
                ]
            ],
            'platnosc' => [
                'terminPlatnosci' => [
                    [
                        'termin' => '2022-03-15'
                    ]
                ],
                'platnoscGroup' => [
                    'formaPlatnosci' => '6'
                ],
                'rachunekBankowy' => [
                    [
                        'nrRBGroup' => [
                            'nrRB' => '73111111111111111111111111'
                        ],
                        'nazwaBanku' => 'Bank Bankowości Bankowej S. A.',
                        'opisRachunku' => 'PLN'
                    ]
                ]
            ]
        ],
        'stopka' => [
            'informacje' => [
                [
                    'stopkaFaktury' => 'Kapiał zakładowy 5 000 000'
                ]
            ],
            'rejestry' => [
                [
                    'krs' => '0000099999',
                    'regon' => '999999999',
                    'bdo' => '000099999'
                ]
            ]
        ]
    ];
}
