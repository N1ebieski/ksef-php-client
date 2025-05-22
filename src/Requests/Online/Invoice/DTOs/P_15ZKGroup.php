<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\KursWalutyZK;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_15ZK;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class P_15ZKGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_15ZK $p15ZK W przypadku korekt faktur zaliczkowych, kwota zapłaty przed korektą. W przypadku korekt faktur, o których mowa w art. 106f ust. 3 ustawy, kwota pozostała do zapłaty przed korektą
     * @param Optional|KursWalutyZK $kursWalutyZK Kurs waluty stosowany do wyliczenia kwoty podatku w przypadkach, o których mowa w Dziale VI ustawy przed korektą
     */
    public function __construct(
        public P_15ZK $p15ZK,
        public Optional | KursWalutyZK $kursWalutyZK = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p15ZKGroup = $dom->createElement('P_15ZKGroup');
        $dom->appendChild($p15ZKGroup);

        $p15ZK = $dom->createElement('P_15ZK');
        $p15ZK->appendChild($dom->createTextNode((string) $this->p15ZK));

        $p15ZKGroup->appendChild($p15ZK);

        if ($this->kursWalutyZK instanceof KursWalutyZK) {
            $kursWalutyZK = $dom->createElement('KursWalutyZK');
            $kursWalutyZK->appendChild($dom->createTextNode((string) $this->kursWalutyZK));

            $p15ZKGroup->appendChild($kursWalutyZK);
        }

        return $dom;
    }
}
