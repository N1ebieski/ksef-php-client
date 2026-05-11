<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_6;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final class P_6Group extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_6 $p_6 Data dokonania lub zakończenia dostawy towarów lub wykonania usługi lub data otrzymania zapłaty, o której mowa w art. 106b ust. 1 pkt 4 ustawy, o ile taka data jest określona i różni się od daty wystawienia faktury. Pole wypełnia się w przypadku, gdy dla wszystkich pozycji faktury data jest wspólna
     */
    public function __construct(
        public readonly P_6 $p_6,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p6Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_6Group');
        $dom->appendChild($p6Group);

        $p6 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_6');
        $p6->appendChild($dom->createTextNode((string) $this->p_6));

        $p6Group->appendChild($p6);

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(p_6: new P_6($data['P_6']));
    }
}
