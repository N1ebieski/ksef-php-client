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
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\OkresFaGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_6Group;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\PlatnoscInnaGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\UEGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\ZaplataGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\ZaplataCzesciowaGroup;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaPozaKsefFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZalacznikiemFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZVatUEFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZaliczkowaZDodatkowymNabywcaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZaplataCzesciowaFixture;

// --- Podmiot2 identity groups ---

test('fromXml detects NIPGroup for Podmiot2', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot2->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(NIPGroup::class);
    expect($faktura->podmiot2->daneIdentyfikacyjne->idGroup->nip->value)->toBe('9999999999');
});

test('fromXml detects UEGroup for Podmiot2', function (): void {
    $fixture = new FakturaZVatUEFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot2->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(UEGroup::class);
    expect($faktura->podmiot2->daneIdentyfikacyjne->idGroup->kodUE->value)->toBe('DE');
    expect($faktura->podmiot2->daneIdentyfikacyjne->idGroup->nrVatUE->value)->toBe('DE730372668');
});

test('fromXml detects BrakIDGroup for Podmiot2', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['podmiot2']['daneIdentyfikacyjne']['idGroup'] = ['brakID' => '1'];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot2->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(BrakIDGroup::class);
});

test('fromXml detects KrajGroup for Podmiot2', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['podmiot2']['daneIdentyfikacyjne']['idGroup'] = ['nrID' => 'PASS123', 'kodKraju' => 'FR'];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot2->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(KrajGroup::class);
    expect($faktura->podmiot2->daneIdentyfikacyjne->idGroup->nrID->value)->toBe('PASS123');
});

// --- Podmiot3 identity groups ---

test('fromXml detects IDWewGroup for Podmiot3', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['podmiot3'][0]['daneIdentyfikacyjne']['idGroup'] = ['iDWew' => '1234567891-00001'];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot3[0]->daneIdentyfikacyjne->idGroup)->toBeInstanceOf(IDWewGroup::class);
    expect($faktura->podmiot3[0]->daneIdentyfikacyjne->idGroup->iDWew->value)->toBe('1234567891-00001');
});

// --- Fa date group ---

test('fromXml detects P_6Group', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->p_6Group)->toBeInstanceOf(P_6Group::class);
});

test('fromXml detects OkresFaGroup', function (): void {
    $fixture = new FakturaZZalacznikiemFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->p_6Group)->toBeInstanceOf(OkresFaGroup::class);
});

// --- Platnosc payment groups ---

test('fromXml detects ZaplataGroup in Platnosc', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->platnosc->zaplataGroup)->toBeInstanceOf(ZaplataGroup::class);
    expect($faktura->fa->platnosc->platnoscGroup)->toBeInstanceOf(FormaPlatnosciGroup::class);
});

test('fromXml detects ZaplataCzesciowaGroup in Platnosc', function (): void {
    $fixture = new FakturaZZaplataCzesciowaFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->platnosc->zaplataGroup)->toBeInstanceOf(ZaplataCzesciowaGroup::class);
});

test('fromXml detects PlatnoscInnaGroup in Platnosc', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();
    $fixture->data['fa']['platnosc']['platnoscGroup'] = ['platnoscInna' => '1', 'opisPlatnosci' => 'Przelew'];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->platnosc->platnoscGroup)->toBeInstanceOf(PlatnoscInnaGroup::class);
});

// --- KorektaGroup NrKSeF groups ---

test('fromXml detects NrKSeFGroup in DaneFaKorygowanej', function (): void {
    $fixture = new FakturaKorygujacaPozaKsefFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->korektaGroup->daneFaKorygowanej[0]->nrKSeFGroup)
        ->toBeInstanceOf(NrKSeFNGroup::class);
});

test('fromXml detects NrKSeFGroup when NrKSeF is provided', function (): void {
    $fixture = new FakturaKorygujacaPozaKsefFixture();
    $fixture->data['fa']['korektaGroup']['daneFaKorygowanej'][0]['nrKSeFGroup'] = [
        'nrKSeFFaKorygowanej' => '9999999999-20230908-8BEF280C8D35-4D',
    ];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->korektaGroup->daneFaKorygowanej[0]->nrKSeFGroup)
        ->toBeInstanceOf(NrKSeFGroup::class);
});

// --- FakturaZaliczkowa NrKSeF groups ---

test('fromXml detects NrKSeFFaZaliczkowejGroup in FakturaZaliczkowa', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['fa']['fakturaZaliczkowa'] = [[
        'nrKSeFZNGroup' => ['nrKSeFFaZaliczkowej' => '9999999999-20230908-8BEF280C8D35-4D'],
    ]];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->fakturaZaliczkowa)->not->toBeInstanceOf(Optional::class);
    expect($faktura->fa->fakturaZaliczkowa[0]->nrKSeFZNGroup)
        ->toBeInstanceOf(NrKSeFFaZaliczkowejGroup::class);
});

test('fromXml detects NrKSeFZNGroup in FakturaZaliczkowa', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();
    $fixture->data['fa']['fakturaZaliczkowa'] = [[
        'nrKSeFZNGroup' => ['nrFaZaliczkowej' => 'FZ2022/01/001'],
    ]];

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->fakturaZaliczkowa[0]->nrKSeFZNGroup)
        ->toBeInstanceOf(NrKSeFZNGroup::class);
});
