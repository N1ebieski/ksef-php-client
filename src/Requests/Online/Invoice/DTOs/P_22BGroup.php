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
     * @param P_22B $p_22b Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - należy podać przebieg pojazdu
     * @param Optional|P_22BT $p_22bt Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać typ nowego środka transportu
     */
    public function __construct(
        public P_22B $p_22b,
        public Optional | P_22B1Group | P_22B2Group | P_22B3Group | P_22B4Group $p_22b1234group = new Optional(),
        public Optional | P_22BT $p_22bt = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22bgroup = $dom->createElement('P_22BGroup');
        $dom->appendChild($p_22bgroup);

        $p_22b = $dom->createElement('P_22B');
        $p_22b->appendChild($dom->createTextNode((string) $this->p_22b));

        $p_22bgroup->appendChild($p_22b);

        if ( ! $this->p_22b1234group instanceof Optional) {
            /** @var DOMElement $p_22bgroup */
            $p_22b1234group = $this->p_22b1234group->toDom()->documentElement;

            foreach ($p_22b1234group->childNodes as $child) {
                $p_22bgroup->appendChild($dom->importNode($child, true));
            }
        }

        if ($this->p_22bt instanceof P_22BT) {
            $p_22bt = $dom->createElement('P_22BT');
            $p_22bt->appendChild($dom->createTextNode((string) $this->p_22bt));

            $p_22bgroup->appendChild($p_22bt);
        }

        return $dom;
    }
}
