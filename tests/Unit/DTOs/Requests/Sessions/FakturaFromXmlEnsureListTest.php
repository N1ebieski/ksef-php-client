<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZaliczkowaZDodatkowymNabywcaFixture;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrWiersza;

test('fromXml handles single FaWiersz element (not wrapped in array by SimpleXML)', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['fa']['faWiersz'] = [
        [
            'nrWierszaFa' => 1,
            'p_7' => 'Produkt',
            'p_8A' => 'szt',
            'p_8B' => 1,
            'p_9A' => '100.00',
            'p_11' => '100.00',
            'p_12' => '23',
        ]
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->faWiersz)->not->toBeInstanceOf(Optional::class);
    expect($deserialized->fa->faWiersz)->toHaveCount(1);
    expect($deserialized->fa->faWiersz[0]->nrWierszaFa->value)->toBe(1);

    //@phpstan-ignore-next-line cast.string
    expect((string) $deserialized->fa->faWiersz[0]->p_7)->toBe($fixture->data['fa']['faWiersz'][0]['p_7']);
});

test('fromXml handles multiple FaWiersz elements', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->faWiersz)->not->toBeInstanceOf(Optional::class);
    expect($deserialized->fa->faWiersz)->toHaveCount(3);
    expect($deserialized->fa->faWiersz[0]->nrWierszaFa->value)->toBe(1);
    expect($deserialized->fa->faWiersz[1]->nrWierszaFa->value)->toBe(2);
    expect($deserialized->fa->faWiersz[2]->nrWierszaFa->value)->toBe(3);
});

test('fromXml handles single Podmiot3 element', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['podmiot3'] = [$fixture->data['podmiot3'][0]];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot3)->not->toBeInstanceOf(Optional::class);
    expect($deserialized->podmiot3)->toHaveCount(1);

    //@phpstan-ignore-next-line property.notFound
    expect($deserialized->podmiot3[0]->daneIdentyfikacyjne->idGroup->nip->value)->toBe($fixture->data['podmiot3'][0]['daneIdentyfikacyjne']['idGroup']['nip']);
});

test('fromXml handles multiple Podmiot3 elements', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['podmiot3'] = [
        $fixture->data['podmiot3'][0],
        //@phpstan-ignore-next-line argument.type
        array_merge($fixture->data['podmiot3'][0], [
            'daneIdentyfikacyjne' => [
                'idGroup' => ['nip' => '4444444444'],
                'nazwa' => 'Drugi podmiot',
            ],
        ]),
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot3)->not->toBeInstanceOf(Optional::class);
    expect($deserialized->podmiot3)->toHaveCount(2);

    //@phpstan-ignore-next-line property.notFound
    expect($deserialized->podmiot3[0]->daneIdentyfikacyjne->idGroup->nip->value)->toBe($fixture->data['podmiot3'][0]['daneIdentyfikacyjne']['idGroup']['nip']);

    //@phpstan-ignore-next-line property.notFound
    expect($deserialized->podmiot3[1]->daneIdentyfikacyjne->idGroup->nip->value)->toBe($fixture->data['podmiot3'][1]['daneIdentyfikacyjne']['idGroup']['nip']);
});

test('fromXml handles single DaneKontaktowe element', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['podmiot1']['daneKontaktowe'] = [[
        'email' => 'test@example.com',
    ]];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot1->daneKontaktowe)->not->toBeInstanceOf(Optional::class);
    expect($deserialized->podmiot1->daneKontaktowe)->toHaveCount(1);

    //@phpstan-ignore-next-line cast.string
    expect((string) $deserialized->podmiot1->daneKontaktowe[0]->email)->toBe($fixture->data['podmiot1']['daneKontaktowe'][0]['email']);
});

test('fromXml handles absent optional array fields as Optional', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    unset($fixture->data['podmiot3']);

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot3)->toBeInstanceOf(Optional::class);
});

test('fromXml handles single DodatkowyOpis element with NrWiersza', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['fa']['dodatkowyOpis'] = [
        [
            'nrWiersza' => 1,
            'klucz' => 'First key',
            'wartosc' => 'First value',
        ]
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    $dodatkowyOpis = $deserialized->fa->dodatkowyOpis;

    expect($dodatkowyOpis[0]->nrWiersza)->toBeInstanceOf(NrWiersza::class);

    expect($dodatkowyOpis[0]->toArray())->toBeArray()->toEqual($fixture->data['fa']['dodatkowyOpis'][0]);
});

test('fromXml preserves an absent DodatkowyOpis NrWiersza as Optional', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['fa']['dodatkowyOpis'] = [
        [
            'klucz' => 'First key without NrWiersza',
            'wartosc' => 'First value without NrWiersza',
        ]
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    $dodatkowyOpis = $deserialized->fa->dodatkowyOpis;

    expect($dodatkowyOpis[0]->nrWiersza)->toBeInstanceOf(Optional::class);

    expect($dodatkowyOpis[0]->toArray())->toBeArray()->toEqual($fixture->data['fa']['dodatkowyOpis'][0]);
});
