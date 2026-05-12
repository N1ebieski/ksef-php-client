<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\DTOs\Requests\Sessions\BrakIDGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FormaPlatnosciGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\IDWewGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\KrajGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\NIPGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\NrKSeFFaZaliczkowejGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\NrKSeFGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\NrKSeFNGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\NrKSeFZNGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\OkresFaGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_6Group;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\PlatnoscInnaGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\UEGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\ZaplataCzesciowaGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\ZaplataGroup;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaPozaKsefFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZaliczkowaZDodatkowymNabywcaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZVatUEFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZalacznikiemFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZaplataCzesciowaFixture;

test('fromXml detects NIPGroup for Podmiot2', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot2->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(NIPGroup::class);
    expect($deserialized->podmiot2->daneIdentyfikacyjne->idGroup->nip->value)->toBe($fixture->data['podmiot2']['daneIdentyfikacyjne']['idGroup']['nip']);
});

test('fromXml detects UEGroup for Podmiot2', function (): void {
    $fixture = new FakturaZVatUEFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot2->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(UEGroup::class);
    expect($deserialized->podmiot2->daneIdentyfikacyjne->idGroup->kodUE->value)->toBe($fixture->data['podmiot2']['daneIdentyfikacyjne']['idGroup']['kodUE']);
    expect($deserialized->podmiot2->daneIdentyfikacyjne->idGroup->nrVatUE->value)->toBe($fixture->data['podmiot2']['daneIdentyfikacyjne']['idGroup']['nrVatUE']);
});

test('fromXml detects BrakIDGroup for Podmiot2', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['podmiot2']['daneIdentyfikacyjne']['idGroup'] = [
        'brakID' => '1'
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot2->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(BrakIDGroup::class);
});

test('fromXml detects KrajGroup for Podmiot2', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['podmiot2']['daneIdentyfikacyjne']['idGroup'] = [
        'nrID' => 'PASS123',
        'kodKraju' => 'FR'
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot2->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(KrajGroup::class);
    expect($deserialized->podmiot2->daneIdentyfikacyjne->idGroup->nrID->value)->toBe($fixture->data['podmiot2']['daneIdentyfikacyjne']['idGroup']['nrID']);
});

test('fromXml detects IDWewGroup for Podmiot3', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['podmiot3'][0]['daneIdentyfikacyjne']['idGroup'] = [
        'iDWew' => '1234567891-00001'
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot3[0]->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(IDWewGroup::class);
    expect($deserialized->podmiot3[0]->daneIdentyfikacyjne->idGroup->iDWew->value)->toBe($fixture->data['podmiot3'][0]['daneIdentyfikacyjne']['idGroup']['iDWew']);
});

test('fromXml detects P_6Group', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->p_6Group)->toBeInstanceOf(P_6Group::class);
});

test('fromXml detects OkresFaGroup', function (): void {
    $fixture = new FakturaZZalacznikiemFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->p_6Group)->toBeInstanceOf(OkresFaGroup::class);
});

test('fromXml detects ZaplataGroup in Platnosc', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->platnosc->zaplataGroup)->toBeInstanceOf(ZaplataGroup::class);
    expect($deserialized->fa->platnosc->platnoscGroup)->toBeInstanceOf(FormaPlatnosciGroup::class);
});

test('fromXml detects ZaplataCzesciowaGroup in Platnosc', function (): void {
    $fixture = new FakturaZZaplataCzesciowaFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->platnosc->zaplataGroup)->toBeInstanceOf(ZaplataCzesciowaGroup::class);
});

test('fromXml detects PlatnoscInnaGroup in Platnosc', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['fa']['platnosc']['platnoscGroup'] = [
        'platnoscInna' => '1',
        'opisPlatnosci' => 'Przelew'
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->platnosc->platnoscGroup)->toBeInstanceOf(PlatnoscInnaGroup::class);
});

test('fromXml detects NrKSeFGroup in DaneFaKorygowanej', function (): void {
    $fixture = new FakturaKorygujacaPozaKsefFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->korektaGroup->daneFaKorygowanej[0]->nrKSeFGroup)
        ->toBeInstanceOf(NrKSeFNGroup::class);
});

test('fromXml detects NrKSeFGroup when NrKSeF is provided', function (): void {
    $fixture = new FakturaKorygujacaPozaKsefFixture();
    $fixture->data['fa']['korektaGroup']['daneFaKorygowanej'][0]['nrKSeFGroup'] = [
        'nrKSeFFaKorygowanej' => '9999999999-20230908-8BEF280C8D35-4D',
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->korektaGroup->daneFaKorygowanej[0]->nrKSeFGroup)
        ->toBeInstanceOf(NrKSeFGroup::class);
});

test('fromXml detects NrKSeFFaZaliczkowejGroup in FakturaZaliczkowa', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['fa']['fakturaZaliczkowa'] = [
        [
            'nrKSeFZNGroup' => ['nrKSeFFaZaliczkowej' => '9999999999-20230908-8BEF280C8D35-4D'],
        ]
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->fakturaZaliczkowa)->not->toBeInstanceOf(Optional::class);
    expect($deserialized->fa->fakturaZaliczkowa[0]->nrKSeFZNGroup)
        ->toBeInstanceOf(NrKSeFFaZaliczkowejGroup::class);
});

test('fromXml detects NrKSeFZNGroup in FakturaZaliczkowa', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['fa']['fakturaZaliczkowa'] = [
        [
            'nrKSeFZNGroup' => ['nrFaZaliczkowej' => 'FZ2022/01/001'],
        ]
    ];

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->fakturaZaliczkowa[0]->nrKSeFZNGroup)
        ->toBeInstanceOf(NrKSeFZNGroup::class);
});
