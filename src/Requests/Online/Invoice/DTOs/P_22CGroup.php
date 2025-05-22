<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22C1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22C;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class P_22CGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22C $p_22C Jeśli dostawa dotyczy jednostek pływających, o których mowa w art. 2 pkt 10 lit. b ustawy, należy podać liczbę godzin roboczych używania nowego środka transportu
     * @param Optional|P_22C1 $p_22C1 Jeśli dostawa dotyczy jednostek pływających, o których mowa w art. 2 pkt 10 lit. b ustawy, można podać numer kadłuba nowego środka transportu
     */
    public function __construct(
        public P_22C $p_22C,
        public Optional | P_22C1 $p_22C1 = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22CGroup = $dom->createElement('P_22CGroup');
        $dom->appendChild($p_22CGroup);

        $p_22C = $dom->createElement('P_22C');
        $p_22C->appendChild($dom->createTextNode((string) $this->p_22C));

        $p_22CGroup->appendChild($p_22C);

        if ($this->p_22C1 instanceof P_22C1) {
            $p_22C1 = $dom->createElement('P_22C1');
            $p_22C1->appendChild($dom->createTextNode((string) $this->p_22C1));

            $p_22CGroup->appendChild($p_22C1);
        }

        return $dom;
    }
}
