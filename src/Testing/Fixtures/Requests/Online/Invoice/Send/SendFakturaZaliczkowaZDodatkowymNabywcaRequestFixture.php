<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

final class SendFakturaZaliczkowaZDodatkowymNabywcaRequestFixture extends AbstractSendRequestFixture
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
            'podmiot3' => [
                [
                    'daneIdentyfikacyjne' => [
                        'idGroup' => [
                            'nip' => '3333333333'
                        ],
                        'nazwa' => 'F.H.U. Grażyna Kowalska'
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
                    'rolaGroup' => [
                        'rola' => '4'
                    ],
                    'udzial' => 50
                ]
            ],
            'fa' => [
                'kodWaluty' => 'PLN',
                'p_1' => '2025-05-11',
                'p_1M' => 'Warszawa',
                'p_2' => 'FZ2022/02/150',
                'p_6Group' => [
                    'p_6' => '2025-05-11'
                ],
                'p_13_1Group' => [
                    'p_13_1' => 16260.16,
                    'p_14_1' => 3739.84,
                ],
                'p_15' => 20000,
                'rodzajFaktury' => 'ZAL',
                'dodatkowyOpis' => [
                    [
                        'klucz' => 'wysokosć wpłaconego zadatku',
                        'wartosc' => '20000 zł'
                    ]
                ],
                'platnosc' => [
                    'zaplataGroup' => [
                        'dataZaplaty' => '2022-02-15'
                    ],
                    'platnoscGroup' => [
                        'formaPlatnosci' => '6'
                    ]
                ],
                'zamowienie' => [
                    'wartoscZamowienia' => 375150,
                    'zamowienieWiersz' => [
                        [
                            'nrWierszaZam' => 1,
                            'uu_idZ' => 'aaaa111133339990',
                            'p_7Z' => 'mieszkanie 50m^2',
                            'p_8AZ' => 'szt.',
                            'p_8BZ' => 1,
                            'p_9AZ' => 300000,
                            'p_11NettoZ' => 300000,
                            'p_11VatZ' => 69000,
                            'p_12Z' => '23',
                        ],
                        [
                            'nrWierszaZam' => 2,
                            'uu_idZ' => 'aaaa111133339991',
                            'p_7Z' => 'usługi dodatkowe',
                            'p_8AZ' => 'szt.',
                            'p_8BZ' => 1,
                            'p_9AZ' => 5000,
                            'p_11NettoZ' => 5000,
                            'p_11VatZ' => 1150,
                            'p_12Z' => '23',
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
