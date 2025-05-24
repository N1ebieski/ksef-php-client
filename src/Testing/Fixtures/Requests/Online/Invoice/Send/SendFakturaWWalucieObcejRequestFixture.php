<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

final class SendFakturaWWalucieObcejRequestFixture extends AbstractSendRequestFixture
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
                    'nazwa' => 'Biuro Podróży ABC sp. z o. o.'
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
                ],
                'nrKlienta' => 'fdfd778343'
            ],
            'fa' => [
                'kodWaluty' => 'EUR',
                'p_1' => '2025-05-11',
                'p_1M' => 'Warszawa',
                'p_2' => 'FV2022/02/150',
                'p_13_1Group' => [
                    'p_13_1' => 13560,
                    'p_14_1' => 3118.80,
                    'p_14_1W' => 13768.14
                ],
                'p_15' => 16678.80,
                'rodzajFaktury' => 'VAT',
                'faWiersz' => [
                    [
                        'nrWierszaFa' => 1,
                        'uu_id' => 'aaaa111133339990',
                        'p_6A' => '2022-02-05',
                        'p_7' => 'lodówka Zimnotech mk1',
                        'cn' => '8418 21 91',
                        'p_8A' => 'szt.',
                        'p_8B' => 10,
                        'p_9A' => 406,
                        'p_11' => 4060,
                        'p_12' => '23',
                        'kursWaluty' => 4.4080
                    ],
                    [
                        'nrWierszaFa' => 2,
                        'uu_id' => 'aaaa111133339991',
                        'p_6A' => '2022-02-10',
                        'p_7' => 'zamrażarka Zimnotech mk2',
                        'cn' => '8418 40 20',
                        'p_8A' => 'szt.',
                        'p_8B' => 20,
                        'p_9A' => 250,
                        'p_11' => 5000,
                        'p_12' => '23',
                        'kursWaluty' => 4.5005
                    ],
                    [
                        'nrWierszaFa' => 3,
                        'uu_id' => 'aaaa111133339992',
                        'p_6A' => '2022-02-20',
                        'p_7' => 'zmywarka Bryza 100',
                        'cn' => '8422 11 00',
                        'p_8A' => 'szt.',
                        'p_8B' => 15,
                        'p_9A' => 300,
                        'p_11' => 4500,
                        'p_12' => '23',
                        'kursWaluty' => 4.3250
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
                    'rachunekBankowyFaktora' => [
                        [
                            'nrRBGroup' => [
                                'nrRB' => '73111111111111111111111111',
                            ],
                            'rachunekWlasnyBanku' => '2',
                            'nazwaBanku' => 'Bank Bankowości Bankowej S. A.',
                            'opisRachunku' => 'PLN'
                        ]
                    ]
                ],
                'warunkiTransakcji' => [
                    'zamowienia' => [
                        [
                            'dataZamowienia' => '2022-01-26',
                            'nrZamowienia' => '4354343'
                        ]
                    ],
                    'nrPartiiTowaru' => [
                        '2312323/2022'
                    ],
                    'warunkiDostawy' => 'CIP',
                    'transport' => [
                        [
                            'transportGroup' => [
                                'rodzajTransportu' => '3'
                            ],
                            'przewoznik' => [
                                'daneIdentyfikacyjne' => [
                                    'idGroup' => [
                                        'nip' => '6666666666'
                                    ],
                                    'nazwa' => 'Jan Nowak Transport'
                                ],
                                'adresPrzewoznika' => [
                                    'kodKraju' => 'PL',
                                    'adresL1' => 'ul. Bukowa 5',
                                    'adresL2' => '00-004 Poznań'
                                ]
                            ],
                            'ladunekGroup' => [
                                'opisLadunkuGroup' => [
                                    'opisLadunku' => '13'
                                ],
                                'jednostkaOpakowania' => 'a'
                            ],
                            'wysylkaGroup' => [
                                'wysylkaZ' => [
                                    'kodKraju' => 'PL',
                                    'adresL1' => 'Sadowa 1 lok. 2',
                                    'adresL2' => '00-001 Warszawa'
                                ],
                                'wysylkaDo' => [
                                    'kodKraju' => 'PL',
                                    'adresL1' => 'ul. Sadowa 1 lok. 3',
                                    'adresL2' => '00-002 Kraków'
                                ]
                            ]
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
        ]
    ];
}
