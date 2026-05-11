<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaDaneNabywcyFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaPozaKsefFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaUniwersalnaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFpTpFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruWithFloatsFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyUslugLeasinguOperacyjnegoFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaUproszczonaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaVatMarzaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaWWalucieObcejFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZaliczkaCzesciowaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZaliczkowaZDodatkowymNabywcaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZVatUEFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZwolnienieVatFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZalacznikiemFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZaplataCzesciowaFixture;

dataset('faktura fixtures', [
    'sprzedaz towaru'                 => [new FakturaSprzedazyTowaruFixture()],
    'sprzedaz towaru z float'         => [new FakturaSprzedazyTowaruWithFloatsFixture()],
    'sprzedaz towaru FP/TP'           => [new FakturaSprzedazyTowaruFpTpFixture()],
    'sprzedaz uslug leasing'          => [new FakturaSprzedazyUslugLeasinguOperacyjnegoFixture()],
    'faktura uproszczona'             => [new FakturaUproszczonaFixture()],
    'faktura w walucie obcej'         => [new FakturaWWalucieObcejFixture()],
    'VAT UE'                          => [new FakturaZVatUEFixture()],
    'zwolnienie VAT'                  => [new FakturaZwolnienieVatFixture()],
    'VAT marza'                       => [new FakturaVatMarzaFixture()],
    'zaliczkowa z dodatkowym nabywca' => [new FakturaZaliczkowaZDodatkowymNabywcaFixture()],
    'zaliczkowa czesc'                => [new FakturaZaliczkaCzesciowaFixture()],
    'zaplata czesciowa'               => [new FakturaZZaplataCzesciowaFixture()],
    'korygujaca poza KSeF'            => [new FakturaKorygujacaPozaKsefFixture()],
    'korygujaca dane nabywcy'         => [new FakturaKorygujacaDaneNabywcyFixture()],
    'korygujaca uniwersalna'          => [new FakturaKorygujacaUniwersalnaFixture()],
    'z zalacznikiem'                  => [new FakturaZZalacznikiemFixture()],
]);

test('fromXml round-trip produces identical XML', function (object $fixture): void {
    $original = Faktura::from($fixture->data);

    $deserialized = Faktura::fromXml($original->toXml());

    expect($deserialized->toXml())->toBe($original->toXml());
})->with('faktura fixtures');
