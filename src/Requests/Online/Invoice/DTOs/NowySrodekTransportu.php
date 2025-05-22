<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22A;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22BK;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22BMD;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22BMK;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22BNR;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22BRP;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_NrWierszaNST;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class NowySrodekTransportu extends AbstractDTO implements DomSerializableInterface
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
        public P_22A $p_22A,
        public P_NrWierszaNST $p_nrWierszaNST,
        public P_22BGroup | P_22CGroup | P_22DGroup $p_22BCDGroup,
        public Optional | P_22BMK $p_22BMK = new Optional(),
        public Optional | P_22BMD $p_22BMD = new Optional(),
        public Optional | P_22BK $p_22BK = new Optional(),
        public Optional | P_22BNR $p_22BNR = new Optional(),
        public Optional | P_22BRP $p_22BRP = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nowySrodekTransportu = $dom->createElement('NowySrodekTransportu');
        $dom->appendChild($nowySrodekTransportu);

        $p_22A = $dom->createElement('P_22A');
        $p_22A->appendChild($dom->createTextNode((string) $this->p_22A));

        $nowySrodekTransportu->appendChild($p_22A);

        $p_nrWierszaNST = $dom->createElement('P_NrWierszaNST');
        $p_nrWierszaNST->appendChild($dom->createTextNode((string) $this->p_nrWierszaNST));

        $nowySrodekTransportu->appendChild($p_nrWierszaNST);

        if ($this->p_22BMK instanceof P_22BMK) {
            $p_22BMK = $dom->createElement('P_22BMK');
            $p_22BMK->appendChild($dom->createTextNode((string) $this->p_22BMK));

            $nowySrodekTransportu->appendChild($p_22BMK);
        }

        if ($this->p_22BMD instanceof P_22BMD) {
            $p_22BMD = $dom->createElement('P_22BMD');
            $p_22BMD->appendChild($dom->createTextNode((string) $this->p_22BMD));

            $nowySrodekTransportu->appendChild($p_22BMD);
        }

        if ($this->p_22BK instanceof P_22BK) {
            $p_22BK = $dom->createElement('P_22BK');
            $p_22BK->appendChild($dom->createTextNode((string) $this->p_22BK));

            $nowySrodekTransportu->appendChild($p_22BK);
        }

        if ($this->p_22BNR instanceof P_22BNR) {
            $p_22BNR = $dom->createElement('P_22BNR');
            $p_22BNR->appendChild($dom->createTextNode((string) $this->p_22BNR));

            $nowySrodekTransportu->appendChild($p_22BNR);
        }

        if ($this->p_22BRP instanceof P_22BRP) {
            $p_22BRP = $dom->createElement('P_22BRP');
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
}
