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
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\RodzajFaktury;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Rola;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\TypKorekty;

test('fromXml populates Naglowek fields correctly', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->naglowek->wariantFormularza)->toBe(FormCode::Fa3);
    expect($deserialized->naglowek->systemInfo)->not->toBeInstanceOf(Optional::class);

    //@phpstan-ignore-next-line cast.string
    expect((string) $deserialized->naglowek->systemInfo)->toBe($fixture->data['naglowek']['systemInfo']);
});

test('fromXml populates Podmiot1 fields correctly', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot1->daneIdentyfikacyjne->nip->value)->toBe($fixture->data['podmiot1']['daneIdentyfikacyjne']['nip']);
    expect($deserialized->podmiot1->daneIdentyfikacyjne->nazwa->value)->toBe($fixture->data['podmiot1']['daneIdentyfikacyjne']['nazwa']);
    expect($deserialized->podmiot1->adres->kodKraju->value)->toBe($fixture->data['podmiot1']['adres']['kodKraju']);
    expect($deserialized->podmiot1->adres->adresL1->value)->toBe($fixture->data['podmiot1']['adres']['adresL1']);
});

test('fromXml populates Fa scalar fields correctly', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->kodWaluty->value)->toBe($fixture->data['fa']['kodWaluty']);
    expect($deserialized->fa->p_2->value)->toBe($fixture->data['fa']['p_2']);
    expect($deserialized->fa->p_15->value)->toBe($fixture->data['fa']['p_15']);
    expect($deserialized->fa->rodzajFaktury)->toBe(RodzajFaktury::Vat);
    expect($deserialized->fa->p_1M)->not->toBeInstanceOf(Optional::class);

    //@phpstan-ignore-next-line cast.string
    expect((string) $deserialized->fa->p_1M)->toBe($fixture->data['fa']['p_1M']);
});

test('fromXml populates FaWiersz array correctly', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->fa->faWiersz)->not->toBeInstanceOf(Optional::class);

    //@phpstan-ignore-next-line argument.type
    expect($deserialized->fa->faWiersz)->toHaveCount(count($fixture->data['fa']['faWiersz']));

    $wiersz = $deserialized->fa->faWiersz[0];

    expect($wiersz->nrWierszaFa->value)->toBe(1);

    //@phpstan-ignore-next-line cast.string
    expect((string) $wiersz->p_7)->toBe($fixture->data['fa']['faWiersz'][0]['p_7']);

    //@phpstan-ignore-next-line cast.string
    expect((string) $wiersz->p_8A)->toBe($fixture->data['fa']['faWiersz'][0]['p_8A']);

    //@phpstan-ignore-next-line cast.string
    expect((string) $wiersz->p_8B)->toBe((string) $fixture->data['fa']['faWiersz'][0]['p_8B']);
    expect($wiersz->p_12)->toBe(P_12::Tax23);
});

test('fromXml populates Zwolnienie with P_19Group correctly', function (): void {
    $fixture = new FakturaZwolnienieVatFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    $zwolnienie = $deserialized->fa->adnotacje->zwolnienie;

    expect($zwolnienie->p_19Group)->toBeInstanceOf(P_19Group::class);

    /** @var P_19Group $p_19Group */
    $p_19Group = $zwolnienie->p_19Group;

    expect($p_19Group->p_19ABCGroup)->toBeInstanceOf(P_19AGroup::class);

    //@phpstan-ignore-next-line cast.string
    expect((string) $p_19Group->p_19ABCGroup->p_19A)
        ->toBe($fixture->data['fa']['adnotacje']['zwolnienie']['p_19Group']['p_19ABCGroup']['p_19A']);
});

test('fromXml populates PMarzy with P_PMarzyGroup correctly', function (): void {
    $fixture = new FakturaVatMarzaFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    $pMarzy = $deserialized->fa->adnotacje->pMarzy;

    expect($pMarzy->p_PMarzyGroup)->toBeInstanceOf(P_PMarzyGroup::class);

    /** @var P_PMarzyGroup $p_PMarzyGroup */
    $p_PMarzyGroup = $pMarzy->p_PMarzyGroup;

    expect($p_PMarzyGroup->p_PMarzy_2_3Group)->toBeInstanceOf(P_PMarzy_2Group::class);
});

test('fromXml populates Podmiot3 with Rola and Udzial correctly', function (): void {
    $fixture = new FakturaZaliczkowaZDodatkowymNabywcaFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    expect($deserialized->podmiot3)->not->toBeInstanceOf(Optional::class);
    expect($deserialized->podmiot3)->toHaveCount(1);

    $podmiot3 = $deserialized->podmiot3[0];

    expect($podmiot3->rolaGroup)->toBeInstanceOf(RolaGroup::class);

    //@phpstan-ignore-next-line property.notFound
    expect($podmiot3->rolaGroup->rola)->toBe(Rola::DodatkowyNabywca);

    //@phpstan-ignore-next-line cast.string
    expect((string) $podmiot3->udzial)->toBe($fixture->data['podmiot3'][0]['udzial']);
});

test('fromXml populates KorektaGroup fields correctly', function (): void {
    $fixture = new FakturaKorygujacaUniwersalnaFixture();

    $faktura = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($faktura->toXml());

    /** @var KorektaGroup $korektaGroup */
    $korektaGroup = $deserialized->fa->korektaGroup;

    expect($korektaGroup)->toBeInstanceOf(KorektaGroup::class);
    expect($korektaGroup->typKorekty)->toBe(TypKorekty::Inna);

    //@phpstan-ignore-next-line cast.string
    expect((string) $korektaGroup->przyczynaKorekty)
        ->toContain($fixture->data['fa']['korektaGroup']['przyczynaKorekty']);

    expect($korektaGroup->daneFaKorygowanej)->toHaveCount(1);
    expect((string) $korektaGroup->daneFaKorygowanej[0]->nrFaKorygowanej)
        ->toBe($fixture->data['fa']['korektaGroup']['daneFaKorygowanej'][0]['nrFaKorygowanej']);
});
