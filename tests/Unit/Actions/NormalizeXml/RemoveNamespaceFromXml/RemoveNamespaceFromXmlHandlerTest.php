<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Actions\NormalizeXml\RemoveNamespaceFromXml\RemoveNamespaceFromXmlAction;
use N1ebieski\KSEFClient\Actions\NormalizeXml\RemoveNamespaceFromXml\RemoveNamespaceFromXmlHandler;

test('removes namespace from root element', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <Faktura xmlns="http://ksef.mf.gov.pl/schema/gtw/svc/online/types/2021/10/01/0001">
        <NumerFaktury>123/2024</NumerFaktury>
    </Faktura>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->toBeString();
    expect($result)->not->toContain('xmlns=');
    expect($result)->toContain('<Faktura>');
    expect($result)->toContain('<NumerFaktury>123/2024</NumerFaktury>');
});

test('removes namespaces from nested elements', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <tns:Root xmlns:tns="http://example.com/ns1" xmlns:ns2="http://example.com/ns2">
        <tns:Child1>
            <ns2:Child2>
                <tns:Child3>Value</tns:Child3>
            </ns2:Child2>
        </tns:Child1>
    </tns:Root>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->not->toContain('xmlns:tns=');
    expect($result)->not->toContain('xmlns:ns2=');
    expect($result)->not->toContain('tns:');
    expect($result)->not->toContain('ns2:');
    expect($result)->toContain('<Root>');
    expect($result)->toContain('<Child1>');
    expect($result)->toContain('<Child2>');
    expect($result)->toContain('<Child3>Value</Child3>');
});

test('preserves XML attributes', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <ns:Root xmlns:ns="http://example.com" attr1="value1" attr2="value2">
        <ns:Element id="123" type="test">Content</ns:Element>
    </ns:Root>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->not->toContain('xmlns:ns=');
    expect($result)->not->toContain('ns:');
    expect($result)->toContain('attr1="value1"');
    expect($result)->toContain('attr2="value2"');
    expect($result)->toContain('id="123"');
    expect($result)->toContain('type="test"');
    expect($result)->toContain('<Element');
});

test('preserves CDATA sections', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <ns:Root xmlns:ns="http://example.com">
        <ns:Data><![CDATA[<script>alert('test');</script>]]></ns:Data>
    </ns:Root>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->not->toContain('xmlns:ns=');
    expect($result)->not->toContain('ns:');
    expect($result)->toContain("<![CDATA[<script>alert('test');</script>]]>");
});

test('preserves XML comments', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <ns:Root xmlns:ns="http://example.com">
        <!-- This is a comment -->
        <ns:Element>Value</ns:Element>
    </ns:Root>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->not->toContain('xmlns:ns=');
    expect($result)->not->toContain('ns:');
    expect($result)->toContain('<!-- This is a comment -->');
});

test('handles complex real-world KSEF invoice structure', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <tns:Faktura xmlns:tns="http://ksef.mf.gov.pl/schema/gtw/svc/online/types/2021/10/01/0001" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <tns:Podmiot1>
            <tns:DaneIdentyfikacyjne>
                <tns:NIP>1111111111</tns:NIP>
                <tns:Nazwa>Test Company</tns:Nazwa>
            </tns:DaneIdentyfikacyjne>
            <tns:Adres>
                <tns:KodKraju>PL</tns:KodKraju>
            </tns:Adres>
        </tns:Podmiot1>
        <tns:Fa>
            <tns:FaWiersz>
                <tns:NrWierszaFa>1</tns:NrWierszaFa>
                <tns:P_12>6.23</tns:P_12>
            </tns:FaWiersz>
        </tns:Fa>
    </tns:Faktura>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->not->toContain('xmlns');
    expect($result)->not->toContain('tns:');
    expect($result)->toContain('<Faktura>');
    expect($result)->toContain('<Podmiot1>');
    expect($result)->toContain('<DaneIdentyfikacyjne>');
    expect($result)->toContain('<NIP>1111111111</NIP>');
    expect($result)->toContain('<Nazwa>Test Company</Nazwa>');
    expect($result)->toContain('<FaWiersz>');
    expect($result)->toContain('<NrWierszaFa>1</NrWierszaFa>');
});

test('handles multiple namespace declarations', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <root xmlns="http://default.ns" xmlns:a="http://ns.a" xmlns:b="http://ns.b" xmlns:c="http://ns.c">
        <a:element1>Value1</a:element1>
        <b:element2 c:attr="test">Value2</b:element2>
    </root>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->not->toContain('xmlns');
    expect($result)->not->toContain('a:');
    expect($result)->not->toContain('b:');
    expect($result)->not->toContain('c:');
    expect($result)->toContain('<root>');
    expect($result)->toContain('<element1>Value1</element1>');
    expect($result)->toContain('<element2');
    expect($result)->toContain('attr="test"');
});

test('throws RuntimeException on invalid XML', function (): void {
    expect(fn (): string => getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction('<not valid xml')
    ))->toThrow(RuntimeException::class, 'Invalid XML provided');
});

test('handles malformed XML with unclosed tags', function (): void {
    expect(fn (): string => getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction('<root><unclosed>')
    ))->toThrow(RuntimeException::class, 'Invalid XML provided');
});

test('preserves text node values with special characters', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <ns:Root xmlns:ns="http://example.com">
        <ns:Text>Value with &amp; &lt; &gt; special chars</ns:Text>
    </ns:Root>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->not->toContain('xmlns:ns=');
    expect($result)->toContain('&amp;');
    expect($result)->toContain('&lt;');
    expect($result)->toContain('&gt;');
});

test('returns properly formatted XML with declaration', function (): void {
    $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <ns:Root xmlns:ns="http://example.com">
        <ns:Element>Value</ns:Element>
    </ns:Root>
    XML;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)->toStartWith('<?xml version="1.0" encoding="UTF-8"?>');
});

function getRemoveNamespaceFromXmlHandler(): RemoveNamespaceFromXmlHandler
{
    return new RemoveNamespaceFromXmlHandler();
}
