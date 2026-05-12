<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\KorektaGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_19AGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_19Group;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_PMarzy_2Group;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_PMarzyGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\RolaGroup;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaUniwersalnaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaVatMarzaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZaliczkowaZDodatkowymNabywcaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZwolnienieVatFixture;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_12;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Rola;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\RodzajFaktury;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\TypKorekty;

test('fromXml populates Naglowek fields correctly', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->naglowek->wariantFormularza)->toBe(FormCode::Fa3);
    expect($faktura->naglowek->systemInfo)->not->toBeInstanceOf(Optional::class);
    expect((string) $faktura->naglowek->systemInfo)->toBe('KSEF-PHP-Client');
});

test('fromXml populates Podmiot1 fields correctly', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot1->daneIdentyfikacyjne->nip->value)->toBe('1111111111');
    expect($faktura->podmiot1->daneIdentyfikacyjne->nazwa->value)->toBe('Testowa Firma');
    expect($faktura->podmiot1->adres->kodKraju->value)->toBe('PL');
    expect($faktura->podmiot1->adres->adresL1->value)->toBe('30-549 Kraków');
});

test('fromXml populates Fa scalar fields correctly', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->kodWaluty->value)->toBe('PLN');
    expect($faktura->fa->p_2->value)->toBe('1/05/2025');
    expect($faktura->fa->p_15->value)->toBe('2050.99');
    expect($faktura->fa->rodzajFaktury)->toBe(RodzajFaktury::Vat);
    expect($faktura->fa->p_1M)->not->toBeInstanceOf(Optional::class);
    expect((string) $faktura->fa->p_1M)->toBe('Warszawa');
});

test('fromXml populates FaWiersz array correctly', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->fa->faWiersz)->not->toBeInstanceOf(Optional::class);
    expect($faktura->fa->faWiersz)->toHaveCount(3);

    $wiersz = $faktura->fa->faWiersz[0];
    expect($wiersz->nrWierszaFa->value)->toBe(1);
    expect((string) $wiersz->p_7)->toBe('lodówka Zimnotech mk1');
    expect((string) $wiersz->p_8A)->toBe('szt');
    expect((string) $wiersz->p_8B)->toBe('1');
    expect($wiersz->p_12)->toBe(P_12::Tax23);
});

test('fromXml populates Zwolnienie with P_19Group correctly', function (): void {
    $fixture = new FakturaZwolnienieVatFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    $zwolnienie = $faktura->fa->adnotacje->zwolnienie;
    expect($zwolnienie->p_19Group)->toBeInstanceOf(P_19Group::class);

    /** @var P_19Group $p_19Group */
    $p_19Group = $zwolnienie->p_19Group;
    expect($p_19Group->p_19ABCGroup)->toBeInstanceOf(P_19AGroup::class);
    expect((string) $p_19Group->p_19ABCGroup->p_19A)
        ->toBe('art. 43. ust. 1 pkt 29 lit. a Ustawa o VAT');
});

test('fromXml populates PMarzy with P_PMarzyGroup correctly', function (): void {
    $fixture = new FakturaVatMarzaFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    $pMarzy = $faktura->fa->adnotacje->pMarzy;
    expect($pMarzy->p_PMarzyGroup)->toBeInstanceOf(P_PMarzyGroup::class);

    /** @var P_PMarzyGroup $p_PMarzyGroup */
    $p_PMarzyGroup = $pMarzy->p_PMarzyGroup;
    expect($p_PMarzyGroup->p_PMarzy_2_3Group)->toBeInstanceOf(P_PMarzy_2Group::class);
});

test('fromXml populates Podmiot3 with Rola and Udzial correctly', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    expect($faktura->podmiot3)->not->toBeInstanceOf(Optional::class);
    expect($faktura->podmiot3)->toHaveCount(1);

    $podmiot3 = $faktura->podmiot3[0];
    expect($podmiot3->rolaGroup)->toBeInstanceOf(RolaGroup::class);
    expect($podmiot3->rolaGroup->rola)->toBe(Rola::DodatkowyNabywca);
    expect((string) $podmiot3->udzial)->toBe('50');
});

test('fromXml populates KorektaGroup fields correctly', function (): void {
    $fixture = new FakturaKorygujacaUniwersalnaFixture();

    $faktura = Faktura::fromXml(Faktura::from($fixture->data)->toXml());

    $korektaGroup = $faktura->fa->korektaGroup;
    expect($korektaGroup)->toBeInstanceOf(KorektaGroup::class);

    /** @var KorektaGroup $korektaGroup */
    expect($korektaGroup->typKorekty)->toBe(TypKorekty::Inna);
    expect((string) $korektaGroup->przyczynaKorekty)
        ->toContain('obniżka ceny');
    expect($korektaGroup->daneFaKorygowanej)->toHaveCount(1);
    expect((string) $korektaGroup->daneFaKorygowanej[0]->nrFaKorygowanej)
        ->toBe('FV2022/02/150');
});
