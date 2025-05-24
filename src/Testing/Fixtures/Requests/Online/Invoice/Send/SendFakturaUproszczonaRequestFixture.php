<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

final class SendFakturaUproszczonaRequestFixture extends AbstractSendRequestFixture
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
                ],
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
                    'p_13_1' => 365.85,
                    'p_14_1' => 84.15,
                ],
                'p_15' => 450,
                'rodzajFaktury' => 'UPR',
                'faWiersz' => [
                    [
                        'nrWierszaFa' => 1,
                        'p_7' => 'wiertarka Wiertex mk5',
                    ],
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
