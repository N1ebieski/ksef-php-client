<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22B;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22BT;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class P_22BGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22B $p_22B Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - należy podać przebieg pojazdu
     * @param Optional|P_22BT $p_22BT Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać typ nowego środka transportu
     */
    public function __construct(
        public P_22B $p_22B,
        public Optional | P_22B1Group | P_22B2Group | P_22B3Group | P_22B4Group $p_22B1234Group = new Optional(),
        public Optional | P_22BT $p_22BT = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22BGroup = $dom->createElement('P_22BGroup');
        $dom->appendChild($p_22BGroup);

        $p_22B = $dom->createElement('P_22B');
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
            $p_22BT = $dom->createElement('P_22BT');
            $p_22BT->appendChild($dom->createTextNode((string) $this->p_22BT));

            $p_22BGroup->appendChild($p_22BT);
        }

        return $dom;
    }
}
