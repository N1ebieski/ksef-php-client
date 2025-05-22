<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

final class SendFakturaKorygujacaUniwersalnaRequestFixture extends AbstractSendRequestFixture
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
                'nazwa' => 'F.H.U. Jan Kowalski'
            ],
            'adres' => [
                'kodKraju' => 'PL',
                'adresL1' => 'ul. Polna 1',
                'adresL2' => '00-001 Warszawa'
            ],
            'daneKontaktowe' => [
                [
                    'email' => 'jan@kowalski.pl',
                    'telefon' => '555777999'
                ]
            ],
            'nrKlienta' => 'fdfd778343'
        ],
        'fa' => [
            'kodWaluty' => 'PLN',
            'p_1' => '2025-05-11',
            'p_1M' => 'Warszawa',
            'p_2' => 'FK2022/03/200',
            'p_6Group' => [
                'p_6' => '2025-05-11'
            ],
            'p_13_1Group' => [
                'p_13_1' => -162.60,
                'p_14_1' => -37.40,
            ],
            'p_15' => -200,
            'rodzajFaktury' => 'KOR',
            'korektaGroup' => [
                'przyczynaKorekty' => 'obniżka ceny o 200 zł z uwagi na uszkodzenia estetyczne',
                'typKorekty' => '3',
                'daneFaKorygowanej' => [
                    [
                        'dataWystFaKorygowanej' => '2022-03-20',
                        'nrFaKorygowanej' => 'FV2022/02/150',
                        'nrKSeFGroup' => [
                            'nrKSeFFaKorygowanej' => '9999999999-20230908-8BEF280C8D35-4D'
                        ]
                    ]
                ]
            ],
            'faWiersz' => [
                [
                    'nrWierszaFa' => 1,
                    'uu_id' => 'aaaa111133339990',
                    'p_7' => 'lodówka Zimnotech mk1',
                    'p_8A' => 'szt',
                    'p_8B' => 1,
                    'p_9A' => 1626.01,
                    'p_11' => 1626.01,
                    'p_12' => '23',
                    'stanPrzed' => '1'
                ],
                [
                    'nrWierszaFa' => 1,
                    'uu_id' => 'aaaa111133339990',
                    'p_7' => 'lodówka Zimnotech mk1',
                    'p_8A' => 'szt',
                    'p_8B' => 1,
                    'p_9A' => 1463.41,
                    'p_11' => 1463.41,
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
    ];
}
