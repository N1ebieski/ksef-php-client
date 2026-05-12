<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrZleceniaTransportu;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Transport extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    public function __construct(
        public readonly RodzajTransportuGroup | TransportInnyGroup $transportGroup,
        public readonly LadunekGroup $ladunekGroup,
        public readonly Optional | Przewoznik $przewoznik = new Optional(),
        public readonly Optional | NrZleceniaTransportu $nrZleceniaTransportu = new Optional(),
        public readonly Optional | WysylkaGroup $wysylkaGroup = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $transport = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Transport');
        $dom->appendChild($transport);

        /** @var DOMElement $transportGroup */
        $transportGroup = $dom->importNode($this->transportGroup->toDom()->documentElement, true);

        foreach ($transportGroup->childNodes as $child) {
            $transport->appendChild($dom->importNode($child, true));
        }

        if ($this->przewoznik instanceof Przewoznik) {
            $przewoznik = $dom->importNode($this->przewoznik->toDom()->documentElement, true);

            $transport->appendChild($przewoznik);
        }

        if ($this->nrZleceniaTransportu instanceof NrZleceniaTransportu) {
            $nrZleceniaTransportu = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrZleceniaTransportu');
            $nrZleceniaTransportu->appendChild($dom->createTextNode((string) $this->nrZleceniaTransportu));

            $transport->appendChild($nrZleceniaTransportu);
        }

        /** @var DOMElement $ladunekGroup */
        $ladunekGroup = $dom->importNode($this->ladunekGroup->toDom()->documentElement, true);

        foreach ($ladunekGroup->childNodes as $child) {
            $transport->appendChild($dom->importNode($child, true));
        }

        if ($this->wysylkaGroup instanceof WysylkaGroup) {
            /** @var DOMElement $wysylkaGroup */
            $wysylkaGroup = $dom->importNode($this->wysylkaGroup->toDom()->documentElement, true);

            foreach ($wysylkaGroup->childNodes as $child) {
                $transport->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['TransportGroup'] = match (true) {
            isset($data['RodzajTransportu']) => RodzajTransportuGroup::normalizeXmlArray($data),
            default => TransportInnyGroup::normalizeXmlArray($data),
        };

        $data['LadunekGroup'] = LadunekGroup::normalizeXmlArray($data);

        $data['Przewoznik'] = match (true) {
            isset($data['Przewoznik']) => Przewoznik::normalizeXmlArray($data['Przewoznik']),
            default => new Optional(),
        };

        $data['WysylkaGroup'] = match (true) {
            (isset($data['WysylkaPrzez']) || isset($data['WysylkaDo']) || isset($data['WysylkaZ']) || isset($data['DataGodzRozpTransportu']) || isset($data['DataGodzZakTransportu'])) => WysylkaGroup::normalizeXmlArray($data),
            default => new Optional(),
        };

        return Arr::only($data, ['TransportGroup', 'LadunekGroup', 'Przewoznik', 'NrZleceniaTransportu', 'WysylkaGroup']);
    }
}
