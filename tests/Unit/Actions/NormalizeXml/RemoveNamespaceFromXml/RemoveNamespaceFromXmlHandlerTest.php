<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Actions\NormalizeXml\RemoveNamespaceFromXml;

use N1ebieski\KSEFClient\Actions\NormalizeXml\RemoveNamespaceFromXml\RemoveNamespaceFromXmlAction;
use N1ebieski\KSEFClient\Actions\NormalizeXml\RemoveNamespaceFromXml\RemoveNamespaceFromXmlHandler;
use RuntimeException;

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

    expect($result)->not()
        ->toContain('xmlns=')
        ->toContain('<Faktura>')
        ->toContain('<NumerFaktury>123/2024</NumerFaktury>');
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

    expect($result)
        ->toContain('<Root>')
        ->toContain('<Child1>')
        ->toContain('<Child2>')
        ->toContain('<Child3>Value</Child3>')
        ->not()->toContain('xmlns:tns=')
        ->not()->toContain('xmlns:ns2=')
        ->not()->toContain('tns:')
        ->not()->toContain('ns2:');
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

    expect($result)
        ->toContain('attr1="value1"')
        ->toContain('attr2="value2"')
        ->toContain('id="123"')
        ->toContain('type="test"')
        ->toContain('<Element')
        ->not()->toContain('ns:')
        ->not()->toContain('xmlns:ns=');
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

    expect($result)
        ->toContain("<![CDATA[<script>alert('test');</script>]]>")
        ->not()->toContain('xmlns:ns=')
        ->not()->toContain('ns:');
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

    expect($result)
        ->toContain('<!-- This is a comment -->')
        ->not()->toContain('xmlns:ns=')
        ->not()->toContain('ns:');
});

test('handles complex real-world KSEF invoice structure', function (): void {
    $xml = <<<XML_WRAP
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
    XML_WRAP;

    $result = getRemoveNamespaceFromXmlHandler()->handle(
        new RemoveNamespaceFromXmlAction($xml)
    );

    expect($result)
        ->toContain('<Faktura>')
        ->toContain('<Podmiot1>')
        ->toContain('<DaneIdentyfikacyjne>')
        ->toContain('<NIP>1111111111</NIP>')
        ->toContain('<Nazwa>Test Company</Nazwa>')
        ->toContain('<FaWiersz>')
        ->toContain('<NrWierszaFa>1</NrWierszaFa>')
        ->not()->toContain('xmlns')
        ->not()->toContain('tns:');
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

    expect($result)
        ->toContain('<root>')
        ->toContain('<element1>Value1</element1>')
        ->toContain('<element2')
        ->toContain('attr="test"')
        ->not()->toContain('xmlns')
        ->not()->toContain('a:')
        ->not()->toContain('b:')
        ->not()->toContain('c:');
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

    expect($result)
        ->toContain('&amp;')
        ->toContain('&lt;')
        ->toContain('&gt;')
        ->not()->toContain('xmlns:ns=');
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
