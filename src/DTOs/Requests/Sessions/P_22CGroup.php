<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22C1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22C;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_22CGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_22C $p_22C Jeśli dostawa dotyczy jednostek pływających, o których mowa w art. 2 pkt 10 lit. b ustawy, należy podać liczbę godzin roboczych używania nowego środka transportu
     * @param Optional|P_22C1 $p_22C1 Jeśli dostawa dotyczy jednostek pływających, o których mowa w art. 2 pkt 10 lit. b ustawy, można podać numer kadłuba nowego środka transportu
     */
    public function __construct(
        public readonly P_22C $p_22C,
        public readonly Optional | P_22C1 $p_22C1 = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22CGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22CGroup');
        $dom->appendChild($p_22CGroup);

        $p_22C = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22C');
        $p_22C->appendChild($dom->createTextNode((string) $this->p_22C));

        $p_22CGroup->appendChild($p_22C);

        if ($this->p_22C1 instanceof P_22C1) {
            $p_22C1 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22C1');
            $p_22C1->appendChild($dom->createTextNode((string) $this->p_22C1));

            $p_22CGroup->appendChild($p_22C1);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
