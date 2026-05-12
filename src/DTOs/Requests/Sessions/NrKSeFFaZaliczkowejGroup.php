<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrKSeFFaZaliczkowej;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class NrKSeFFaZaliczkowejGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @param NrKSeFFaZaliczkowej $nrKSeFFaZaliczkowej Numer identyfikujący fakturę zaliczkową w KSeF. Pole obowiązkowe w przypadku, gdy faktura zaliczkowa była wystawiona za pomocą KSeF
     */
    public function __construct(
        public readonly NrKSeFFaZaliczkowej $nrKSeFFaZaliczkowej
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFFaZaliczkowejGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFFaZaliczkowejGroup');
        $dom->appendChild($nrKSeFFaZaliczkowejGroup);

        $nrKSeFFaZaliczkowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFFaZaliczkowej');
        $nrKSeFFaZaliczkowej->appendChild($dom->createTextNode($this->nrKSeFFaZaliczkowej->value));

        $nrKSeFFaZaliczkowejGroup->appendChild($nrKSeFFaZaliczkowej);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::only($data, ['NrKSeFFaZaliczkowej']);
    }
}
