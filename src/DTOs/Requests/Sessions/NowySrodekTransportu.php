<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22A;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22BK;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22BMD;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22BMK;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22BNR;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22BRP;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_NrWierszaNST;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final class NowySrodekTransportu extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param P_22A $p_22A Data dopuszczenia nowego środka transportu do użytku
     * @param P_NrWierszaNST $p_nrWierszaNST Numer wiersza faktury, w którym wykazano dostawę nowego środka transportu
     * @param Optional|P_22BMK $p_22BMK Marka nowego środka transportu
     * @param Optional|P_22BMD $p_22BMD Model nowego środka transportu
     * @param Optional|P_22BK $p_22BK Kolor nowego środka transportu
     * @param Optional|P_22BNR $p_22BNR Numer rejestracyjny nowego środka transportu
     * @param Optional|P_22BRP $p_22BRP Rok produkcji nowego środka transportu
     */
    public function __construct(
        public readonly P_22A $p_22A,
        public readonly P_NrWierszaNST $p_nrWierszaNST,
        public readonly P_22BGroup | P_22CGroup | P_22DGroup $p_22BCDGroup,
        public readonly Optional | P_22BMK $p_22BMK = new Optional(),
        public readonly Optional | P_22BMD $p_22BMD = new Optional(),
        public readonly Optional | P_22BK $p_22BK = new Optional(),
        public readonly Optional | P_22BNR $p_22BNR = new Optional(),
        public readonly Optional | P_22BRP $p_22BRP = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nowySrodekTransportu = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NowySrodekTransportu');
        $dom->appendChild($nowySrodekTransportu);

        $p_22A = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22A');
        $p_22A->appendChild($dom->createTextNode((string) $this->p_22A));

        $nowySrodekTransportu->appendChild($p_22A);

        $p_nrWierszaNST = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_NrWierszaNST');
        $p_nrWierszaNST->appendChild($dom->createTextNode((string) $this->p_nrWierszaNST));

        $nowySrodekTransportu->appendChild($p_nrWierszaNST);

        if ($this->p_22BMK instanceof P_22BMK) {
            $p_22BMK = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22BMK');
            $p_22BMK->appendChild($dom->createTextNode((string) $this->p_22BMK));

            $nowySrodekTransportu->appendChild($p_22BMK);
        }

        if ($this->p_22BMD instanceof P_22BMD) {
            $p_22BMD = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22BMD');
            $p_22BMD->appendChild($dom->createTextNode((string) $this->p_22BMD));

            $nowySrodekTransportu->appendChild($p_22BMD);
        }

        if ($this->p_22BK instanceof P_22BK) {
            $p_22BK = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22BK');
            $p_22BK->appendChild($dom->createTextNode((string) $this->p_22BK));

            $nowySrodekTransportu->appendChild($p_22BK);
        }

        if ($this->p_22BNR instanceof P_22BNR) {
            $p_22BNR = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22BNR');
            $p_22BNR->appendChild($dom->createTextNode((string) $this->p_22BNR));

            $nowySrodekTransportu->appendChild($p_22BNR);
        }

        if ($this->p_22BRP instanceof P_22BRP) {
            $p_22BRP = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22BRP');
            $p_22BRP->appendChild($dom->createTextNode((string) $this->p_22BRP));

            $nowySrodekTransportu->appendChild($p_22BRP);
        }

        /** @var DOMElement $p_22BCDGroup */
        $p_22BCDGroup = $this->p_22BCDGroup->toDom()->documentElement;

        foreach ($p_22BCDGroup->childNodes as $child) {
            $nowySrodekTransportu->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        if (isset($data['p_22B'])) {
            $p_22BCDGroup = P_22BGroup::fromXmlArray($data);
        } elseif (isset($data['p_22C'])) {
            $p_22BCDGroup = P_22CGroup::fromXmlArray($data);
        } else {
            $p_22BCDGroup = P_22DGroup::fromXmlArray($data);
        }

        return new self(
            p_22A: new P_22A($data['p_22A']),
            p_nrWierszaNST: new P_NrWierszaNST($data['p_NrWierszaNST']),
            p_22BCDGroup: $p_22BCDGroup,
            p_22BMK: isset($data['p_22BMK']) ? new P_22BMK($data['p_22BMK']) : new Optional(),
            p_22BMD: isset($data['p_22BMD']) ? new P_22BMD($data['p_22BMD']) : new Optional(),
            p_22BK: isset($data['p_22BK']) ? new P_22BK($data['p_22BK']) : new Optional(),
            p_22BNR: isset($data['p_22BNR']) ? new P_22BNR($data['p_22BNR']) : new Optional(),
            p_22BRP: isset($data['p_22BRP']) ? new P_22BRP($data['p_22BRP']) : new Optional(),
        );
    }
}
