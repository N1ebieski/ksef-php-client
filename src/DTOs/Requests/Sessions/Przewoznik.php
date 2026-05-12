<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Przewoznik extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    public function __construct(
        public readonly PrzewoznikDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly AdresPrzewoznika $adresPrzewoznika,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $przewoznik = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Przewoznik');
        $dom->appendChild($przewoznik);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $przewoznik->appendChild($daneIdentyfikacyjne);

        $adresPrzewoznika = $dom->importNode($this->adresPrzewoznika->toDom()->documentElement, true);

        $przewoznik->appendChild($adresPrzewoznika);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneIdentyfikacyjne'] = PrzewoznikDaneIdentyfikacyjne::normalizeXmlArray($data['DaneIdentyfikacyjne']);

        $data['AdresPrzewoznika'] = AdresPrzewoznika::normalizeXmlArray($data['AdresPrzewoznika']);

        return Arr::onlyClassParameters($data, self::class);
    }
}
