<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22D1;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22D;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class P_22DGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22D $p_22d Jeśli dostawa dotyczy statków powietrznych, o których mowa w art. 2 pkt 10 lit. c ustawy, należy podać liczbę godzin roboczych używania nowego środka transportu
     * @param Optional|P_22D1 $p_22d1 Jeśli dostawa dotyczy statków powietrznych, o których mowa w art. 2 pkt 10 lit. c ustawy, można podać numer fabryczny nowego środka transportu
     */
    public function __construct(
        public P_22D $p_22d,
        public Optional | P_22D1 $p_22d1 = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22dgroup = $dom->createElement('P_22DGroup');
        $dom->appendChild($p_22dgroup);

        $p_22d = $dom->createElement('P_22D');
        $p_22d->appendChild($dom->createTextNode((string) $this->p_22d));

        $p_22dgroup->appendChild($p_22d);

        if ($this->p_22d1 instanceof P_22D1) {
            $p_22d1 = $dom->createElement('P_22D1');
            $p_22d1->appendChild($dom->createTextNode((string) $this->p_22d1));

            $p_22dgroup->appendChild($p_22d1);
        }

        return $dom;
    }
}
