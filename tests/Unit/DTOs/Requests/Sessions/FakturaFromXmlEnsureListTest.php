<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZaliczkowaZDodatkowymNabywcaFixture;

test('fromXml handles single FaWiersz element (not wrapped in array by SimpleXML)', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['fa']['faWiersz'] = [[
        'nrWierszaFa' => 1,
        'p_7' => 'Produkt',
        'p_8A' => 'szt',
        'p_8B' => 1,
        'p_9A' => '100.00',
        'p_11' => '100.00',
        'p_12' => '23',
    ]];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->faWiersz)->not->toBeInstanceOf(Optional::class);
    expect($faktura->fa->faWiersz)->toHaveCount(1);
    expect($faktura->fa->faWiersz[0]->nrWierszaFa->value)->toBe(1);
    expect((string) $faktura->fa->faWiersz[0]->p_7)->toBe('Produkt');
});

test('fromXml handles multiple FaWiersz elements', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->faWiersz)->not->toBeInstanceOf(Optional::class);
    expect($faktura->fa->faWiersz)->toHaveCount(3);
    expect($faktura->fa->faWiersz[0]->nrWierszaFa->value)->toBe(1);
    expect($faktura->fa->faWiersz[1]->nrWierszaFa->value)->toBe(2);
    expect($faktura->fa->faWiersz[2]->nrWierszaFa->value)->toBe(3);
});

test('fromXml handles single Podmiot3 element', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['podmiot3'] = [$fixture->data['podmiot3'][0]];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot3)->not->toBeInstanceOf(Optional::class);
    expect($faktura->podmiot3)->toHaveCount(1);
    expect($faktura->podmiot3[0]->daneIdentyfikacyjne->idGroup->nip->value)->toBe('3333333333');
});

test('fromXml handles multiple Podmiot3 elements', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['podmiot3'] = [
        $fixture->data['podmiot3'][0],
        array_merge($fixture->data['podmiot3'][0], [
            'daneIdentyfikacyjne' => [
                'idGroup' => ['nip' => '4444444444'],
                'nazwa' => 'Drugi podmiot',
            ],
        ]),
    ];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot3)->not->toBeInstanceOf(Optional::class);
    expect($faktura->podmiot3)->toHaveCount(2);
    expect($faktura->podmiot3[0]->daneIdentyfikacyjne->idGroup->nip->value)->toBe('3333333333');
    expect($faktura->podmiot3[1]->daneIdentyfikacyjne->idGroup->nip->value)->toBe('4444444444');
});

test('fromXml handles single DaneKontaktowe element', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['podmiot1']['daneKontaktowe'] = [[
        'email' => 'test@example.com',
    ]];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot1->daneKontaktowe)->not->toBeInstanceOf(Optional::class);
    expect($faktura->podmiot1->daneKontaktowe)->toHaveCount(1);
    expect((string) $faktura->podmiot1->daneKontaktowe[0]->email)->toBe('test@example.com');
});

test('fromXml handles absent optional array fields as Optional', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    unset($fixture->data['podmiot3']);

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot3)->toBeInstanceOf(Optional::class);
});
