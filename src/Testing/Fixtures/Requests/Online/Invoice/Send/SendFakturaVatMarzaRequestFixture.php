<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

final class SendFakturaVatMarzaRequestFixture extends AbstractSendRequestFixture
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
                ]
            ],
            'fa' => [
                'kodWaluty' => 'PLN',
                'p_1' => '2025-05-11',
                'p_1M' => 'Warszawa',
                'p_2' => 'FM2022/02/150',
                'p_6Group' => [
                    'p_6' => '2022-02-15',
                ],
                'p_13_1Group' => [
                    'p_13_1' => 813,
                    'p_14_1' => 187,
                ],
                'p_13_11' => 2000,
                'p_15' => 3000,
                'adnotacje' => [
                    'pMarzy' => [
                        'p_PMarzyGroup' => [
                            'p_PMarzy_2_3Group' => [
                                'p_PMarzy_2' => '1'
                            ]
                        ]
                    ]
                ],
                'rodzajFaktury' => 'VAT',
                'faWiersz' => [
                    [
                        'nrWierszaFa' => 1,
                        'p_7' => 'wycieczka na Mazury usługi obce',
                        'p_8A' => 'szt.',
                        'p_8B' => 1,
                        'p_9B' => 2000,
                        'p_11A' => 2000
                    ],
                    [
                        'nrWierszaFa' => 2,
                        'p_7' => 'wycieczka na Mazury usługa własna',
                        'p_8A' => 'szt.',
                        'p_8B' => 1,
                        'p_9B' => 1000,
                        'p_11A' => 1000,
                        'p_12' => '23'
                    ]
                ],
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
