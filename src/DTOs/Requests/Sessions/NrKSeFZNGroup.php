<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrFaZaliczkowej;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrKSeFZN;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class NrKSeFZNGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param NrFaZaliczkowej $nrFaZaliczkowej Numer faktury zaliczkowej wystawionej poza KSeF. Pole obowiązkowe dla faktury wystawianej po wydaniu towaru lub wykonaniu usługi, o której mowa w art. 106f ust. 3 ustawy i ostatniej z faktur, o której mowa w art. 106f ust. 4 ustawy
     * @param NrKSeFZN $nrKSeFZN Znacznik faktury zaliczkowej wystawionej poza KSeF
     */
    public function __construct(
        public readonly NrFaZaliczkowej $nrFaZaliczkowej,
        public readonly NrKSeFZN $nrKSeFZN = NrKSeFZN::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFZNGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFZNGroup');
        $dom->appendChild($nrKSeFZNGroup);

        $nrKSeFZN = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFZN');
        $nrKSeFZN->appendChild($dom->createTextNode((string) $this->nrKSeFZN->value));

        $nrKSeFZNGroup->appendChild($nrKSeFZN);

        $nrFaZaliczkowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrFaZaliczkowej');
        $nrFaZaliczkowej->appendChild($dom->createTextNode((string) $this->nrFaZaliczkowej));

        $nrKSeFZNGroup->appendChild($nrFaZaliczkowej);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
