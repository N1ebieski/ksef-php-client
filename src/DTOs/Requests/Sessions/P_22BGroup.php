<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22B;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22BT;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_22BGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_22B $p_22B Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - należy podać przebieg pojazdu
     * @param Optional|P_22BT $p_22BT Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać typ nowego środka transportu
     */
    public function __construct(
        public readonly P_22B $p_22B,
        public readonly Optional | P_22B1Group | P_22B2Group | P_22B3Group | P_22B4Group $p_22B1234Group = new Optional(),
        public readonly Optional | P_22BT $p_22BT = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22BGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22BGroup');
        $dom->appendChild($p_22BGroup);

        $p_22B = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B');
        $p_22B->appendChild($dom->createTextNode((string) $this->p_22B));

        $p_22BGroup->appendChild($p_22B);

        if ( ! $this->p_22B1234Group instanceof Optional) {
            /** @var DOMElement $p_22B1234Group */
            $p_22B1234Group = $this->p_22B1234Group->toDom()->documentElement;

            foreach ($p_22B1234Group->childNodes as $child) {
                $p_22BGroup->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->p_22BT instanceof P_22BT) {
            $p_22BT = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22BT');
            $p_22BT->appendChild($dom->createTextNode((string) $this->p_22BT));

            $p_22BGroup->appendChild($p_22BT);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        if (isset($data['p_22B1'])) {
            $p_22B1234Group = P_22B1Group::fromXmlArray($data);
        } elseif (isset($data['p_22B2'])) {
            $p_22B1234Group = P_22B2Group::fromXmlArray($data);
        } elseif (isset($data['p_22B3'])) {
            $p_22B1234Group = P_22B3Group::fromXmlArray($data);
        } elseif (isset($data['p_22B4'])) {
            $p_22B1234Group = P_22B4Group::fromXmlArray($data);
        } else {
            $p_22B1234Group = new Optional();
        }

        return new self(
            p_22B: new P_22B($data['p_22B']),
            p_22B1234Group: $p_22B1234Group,
            p_22BT: isset($data['p_22BT']) ? new P_22BT($data['p_22BT']) : new Optional(),
        );
    }
}
