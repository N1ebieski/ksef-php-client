<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

final class SendFakturaKorygujacaDaneNabywcyRequestFixture extends AbstractSendRequestFixture
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
                'nrKlienta' => 'fdfd778343',
                'idNabywcy' => '0001'
            ],
            'fa' => [
                'kodWaluty' => 'PLN',
                'p_1' => '2025-05-11',
                'p_1M' => 'Warszawa',
                'p_2' => 'FK2022/03/200',
                'p_15' => 0,
                'rodzajFaktury' => 'KOR',
                'korektaGroup' => [
                    'przyczynaKorekty' => 'błędna nazwa nabywcy',
                    'typKorekty' => '1',
                    'daneFaKorygowanej' => [
                        [
                            'dataWystFaKorygowanej' => '2022-03-20',
                            'nrFaKorygowanej' => 'FV2022/02/150',
                            'nrKSeFGroup' => [
                                'nrKSeFFaKorygowanej' => '9999999999-20230908-8BEF280C8D35-4D'
                            ]
                        ]
                    ],
                    'podmiot2K' => [
                        [
                            'daneIdentyfikacyjne' => [
                                'idGroup' => [
                                    'nip' => '5123957531'
                                ],
                                'nazwa' => 'CDE sp. j.'
                            ],
                            'adres' => [
                                'kodKraju' => 'PL',
                                'adresL1' => 'ul. Sadowa 1 lok. 3',
                                'adresL2' => '00-002 Kraków'
                            ],
                            'idNabywcy' => '0001'
                        ]
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
