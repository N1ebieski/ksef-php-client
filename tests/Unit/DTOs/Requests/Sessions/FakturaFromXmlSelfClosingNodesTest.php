<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Stopka;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Zalacznik;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZalacznikiemFixture;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use RuntimeException;

test('fromXml handles empty nodes in an invoice with an attachment', function (): void {
    $fixture = new FakturaZZalacznikiemFixture();

    $original = Faktura::from($fixture->data);

    $document = new DOMDocument();
    $document->loadXML($original->toXml());

    $xpath = new DOMXPath($document);
    $xpath->registerNamespace('fa', XmlNamespace::Fa3->value);

    $emptyElementNodes = $xpath->query('/fa:Faktura/fa:Stopka | /fa:Faktura/fa:Podmiot1/fa:DaneKontaktowe');

    if ( ! $emptyElementNodes instanceof DOMNodeList) {
        throw new RuntimeException('Unable to query empty XML elements');
    }

    foreach ($emptyElementNodes as $element) {
        if ( ! $element instanceof DOMElement) {
            throw new RuntimeException('Expected an XML element');
        }

        while ($element->firstChild instanceof DOMNode) {
            $element->removeChild($element->firstChild);
        }
    }

    $podmiot1Nodes = $xpath->query('/fa:Faktura/fa:Podmiot1');
    $daneKontaktoweNodes = $xpath->query('/fa:Faktura/fa:Podmiot1/fa:DaneKontaktowe');

    if ( ! $podmiot1Nodes instanceof DOMNodeList || ! $daneKontaktoweNodes instanceof DOMNodeList) {
        throw new RuntimeException('Unable to find Podmiot1 contact data');
    }

    $podmiot1 = $podmiot1Nodes->item(0);
    $daneKontaktowe = $daneKontaktoweNodes->item(0);

    if ( ! $podmiot1 instanceof DOMElement || ! $daneKontaktowe instanceof DOMElement) {
        throw new RuntimeException('Unable to find Podmiot1 contact data');
    }

    $podmiot1->appendChild($daneKontaktowe->cloneNode(true));

    $dashNodes = $xpath->query('//fa:WKom[. = "-"]');

    if ( ! $dashNodes instanceof DOMNodeList) {
        throw new RuntimeException('Unable to query WKom elements');
    }

    foreach ($dashNodes as $element) {
        if ( ! $element instanceof DOMElement) {
            throw new RuntimeException('Expected a WKom XML element');
        }

        if ($element->firstChild instanceof DOMNode) {
            $element->removeChild($element->firstChild);
        }
    }

    $xml = $document->saveXML();

    if ($xml === false) {
        throw new RuntimeException('Unable to serialize XML');
    }

    $deserialized = Faktura::fromXml($xml);

    if ( ! $deserialized->stopka instanceof Stopka) {
        throw new RuntimeException('Expected Stopka DTO');
    }

    if ( ! $deserialized->zalacznik instanceof Zalacznik) {
        throw new RuntimeException('Expected Zalacznik DTO');
    }

    expect($deserialized->stopka->toArray())->toBeEmpty();
    expect($deserialized->podmiot1->daneKontaktowe)->toHaveCount(2);
    expect($deserialized->podmiot1->daneKontaktowe[0]->toArray())->toBeEmpty();
    expect($deserialized->podmiot1->daneKontaktowe[1]->toArray())->toBeEmpty();
    expect($deserialized->zalacznik->blokDanych[0]->tabela[2]->wiersz[1]->wKom[3]->value)->toBeEmpty();
});
