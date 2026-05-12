<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Actions\ConvertXmlToArray\ConvertXmlToArrayAction;
use N1ebieski\KSEFClient\Actions\ConvertXmlToArray\ConvertXmlToArrayHandler;

$ns = 'http://crd.gov.pl/wzor/2025/06/25/13775/';

test('keys match exact XML element names (PascalCase)', function () use ($ns): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <Faktura xmlns="{$ns}">
        <Podmiot1>
            <DaneIdentyfikacyjne>
                <NIP>1111111111</NIP>
                <Nazwa>Test</Nazwa>
            </DaneIdentyfikacyjne>
        </Podmiot1>
    </Faktura>
    XML;

    $array = (new ConvertXmlToArrayHandler())->handle(new ConvertXmlToArrayAction($xml));

    expect($array)->toHaveKey('Podmiot1');
    expect($array['Podmiot1'])->toHaveKey('DaneIdentyfikacyjne');
    expect($array['Podmiot1']['DaneIdentyfikacyjne'])->toHaveKey('NIP');
    expect($array['Podmiot1']['DaneIdentyfikacyjne']['NIP'])->toBe('1111111111');
});

test('all-caps element names are not lowercased', function () use ($ns): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <Root xmlns="{$ns}">
        <NIP>123</NIP>
        <GLN>456</GLN>
        <SWIFT>789</SWIFT>
        <KRS>000</KRS>
        <REGON>111</REGON>
        <BDO>222</BDO>
    </Root>
    XML;

    $array = (new ConvertXmlToArrayHandler())->handle(new ConvertXmlToArrayAction($xml));

    expect($array)->toHaveKey('NIP')->not->toHaveKey('nip')->not->toHaveKey('nIP');
    expect($array)->toHaveKey('GLN')->not->toHaveKey('gln')->not->toHaveKey('gLN');
    expect($array)->toHaveKey('SWIFT')->not->toHaveKey('swift')->not->toHaveKey('sWIFT');
    expect($array)->toHaveKey('KRS')->not->toHaveKey('krs')->not->toHaveKey('kRS');
    expect($array)->toHaveKey('REGON')->not->toHaveKey('regon')->not->toHaveKey('rEGON');
    expect($array)->toHaveKey('BDO')->not->toHaveKey('bdo')->not->toHaveKey('bDO');
});

test('XML attributes are available under @attributes key with original case', function () use ($ns): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <Root xmlns="{$ns}">
        <Kol Typ="txt"><NKom>Nagłówek</NKom></Kol>
    </Root>
    XML;

    $array = (new ConvertXmlToArrayHandler())->handle(new ConvertXmlToArrayAction($xml));

    expect($array['Kol'])->toHaveKey('@attributes');
    expect($array['Kol']['@attributes'])->toHaveKey('Typ');
    expect($array['Kol']['@attributes']['Typ'])->toBe('txt');
    expect($array['Kol']['@attributes'])->not->toHaveKey('typ');
});

test('multiple same-name elements produce indexed array', function () use ($ns): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <Root xmlns="{$ns}">
        <FaWiersz><NrWierszaFa>1</NrWierszaFa></FaWiersz>
        <FaWiersz><NrWierszaFa>2</NrWierszaFa></FaWiersz>
        <FaWiersz><NrWierszaFa>3</NrWierszaFa></FaWiersz>
    </Root>
    XML;

    $array = (new ConvertXmlToArrayHandler())->handle(new ConvertXmlToArrayAction($xml));

    expect($array['FaWiersz'])->toBeArray()->toHaveCount(3);
    expect($array['FaWiersz'][0]['NrWierszaFa'])->toBe('1');
    expect($array['FaWiersz'][1]['NrWierszaFa'])->toBe('2');
    expect($array['FaWiersz'][2]['NrWierszaFa'])->toBe('3');
});

test('single element does not produce indexed array', function () use ($ns): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <Root xmlns="{$ns}">
        <FaWiersz><NrWierszaFa>1</NrWierszaFa></FaWiersz>
    </Root>
    XML;

    $array = (new ConvertXmlToArrayHandler())->handle(new ConvertXmlToArrayAction($xml));

    expect($array['FaWiersz'])->toBeArray();
    expect($array['FaWiersz'])->toHaveKey('NrWierszaFa');
    expect($array['FaWiersz']['NrWierszaFa'])->toBe('1');
});

test('throws RuntimeException on invalid XML', function (): void {
    expect(fn () => (new ConvertXmlToArrayHandler())->handle(
        new ConvertXmlToArrayAction('<not valid xml')
    ))->toThrow(RuntimeException::class);
});
