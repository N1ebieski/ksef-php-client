<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions;

final class FakturaZVatUEFixture extends AbstractFakturaFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'naglowek' => [
            'wariantFormularza' => 'FA (3)',
            'systemInfo' => 'TEST',
        ],
        'podmiot1' => [
            'daneIdentyfikacyjne' => [
                'nip' => '1111111111',
                'nazwa' => 'TEST KOMP',
            ],
            'adres' => [
                'kodKraju' => 'PL',
                'adresL1' => '02-798 Warszawa',
            ],
        ],
        'podmiot2' => [
            'daneIdentyfikacyjne' => [
                'idGroup' => [
                    'kodUE' => 'DE',
                    'nrVatUE' => '730372668',
                ],
                'nazwa' => 'Fussbal A.G.',
            ],
            'adres' => [
                'kodKraju' => 'DE',
                'adresL1' => 'Berlinerstrasse 34, 33-444 Hamburg',
            ],
        ],
        'fa' => [
            'kodWaluty' => 'EUR',
            'p_1' => '2025-11-05',
            'p_1M' => 'Warszawa',
            'p_2' => '7/FV/2025',
            'p_6Group' => [
                'p_6' => '2025-11-05',
            ],
            'p_13_1Group' => [
                'p_13_1' => '100',
                'p_14_1' => '23',
                'p_14_1W' => '97.92',
            ],
            'p_15' => '123',
            'rodzajFaktury' => 'VAT',
            'faWiersz' => [
                [
                    'nrWierszaFa' => 1,
                    'p_7' => 'Test',
                    'p_8A' => 'szt.',
                    'p_8B' => '1',
                    'p_9A' => '100',
                    'p_11' => '100',
                    'p_12' => '23',
                ],
            ],
            'platnosc' => [
                'rachunekBankowy' => [
                    [
                        'nrRBGroup' => [
                            'nrRB' => '73 1160 2202 0000 0004 5122 2732',
                        ],
                        'nazwaBanku' => 'Bank Millenium',
                        'opisRachunku' => 'PLN',
                    ],
                ],
                'terminPlatnosci' => [
                    [
                        'termin' => '2025-11-12',
                    ],
                ],
                'platnoscGroup' => [
                    'formaPlatnosci' => '6',
                ],
            ],
        ],
    ];
}
