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
     * @param P_22A $p_22a Data dopuszczenia nowego środka transportu do użytku
     * @param P_NrWierszaNST $p_nrwierszanst Numer wiersza faktury, w którym wykazano dostawę nowego środka transportu
     * @param Optional|P_22BMK $p_22bmk Marka nowego środka transportu
     * @param Optional|P_22BMD $p_22bmd Model nowego środka transportu
     * @param Optional|P_22BK $p_22bk Kolor nowego środka transportu
     * @param Optional|P_22BNR $p_22bnr Numer rejestracyjny nowego środka transportu
     * @param Optional|P_22BRP $p_22brp Rok produkcji nowego środka transportu
     */
    public function __construct(
        public P_22A $p_22a,
        public P_NrWierszaNST $p_nrwierszanst,
        public P_22BGroup | P_22CGroup | P_22DGroup $p_22bcdgroup,
        public Optional | P_22BMK $p_22bmk = new Optional(),
        public Optional | P_22BMD $p_22bmd = new Optional(),
        public Optional | P_22BK $p_22bk = new Optional(),
        public Optional | P_22BNR $p_22bnr = new Optional(),
        public Optional | P_22BRP $p_22brp = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nowySrodekTransportu = $dom->createElement('NowySrodekTransportu');
        $dom->appendChild($nowySrodekTransportu);

        $p_22a = $dom->createElement('P_22A');
        $p_22a->appendChild($dom->createTextNode((string) $this->p_22a));

        $nowySrodekTransportu->appendChild($p_22a);

        $p_nrwierszanst = $dom->createElement('P_NrWierszaNST');
        $p_nrwierszanst->appendChild($dom->createTextNode((string) $this->p_nrwierszanst));

        $nowySrodekTransportu->appendChild($p_nrwierszanst);

        if ($this->p_22bmk instanceof P_22BMK) {
            $p_22bmk = $dom->createElement('P_22BMK');
            $p_22bmk->appendChild($dom->createTextNode((string) $this->p_22bmk));

            $nowySrodekTransportu->appendChild($p_22bmk);
        }

        if ($this->p_22bmd instanceof P_22BMD) {
            $p_22bmd = $dom->createElement('P_22BMD');
            $p_22bmd->appendChild($dom->createTextNode((string) $this->p_22bmd));

            $nowySrodekTransportu->appendChild($p_22bmd);
        }

        if ($this->p_22bk instanceof P_22BK) {
            $p_22bk = $dom->createElement('P_22BK');
            $p_22bk->appendChild($dom->createTextNode((string) $this->p_22bk));

            $nowySrodekTransportu->appendChild($p_22bk);
        }

        if ($this->p_22bnr instanceof P_22BNR) {
            $p_22bnr = $dom->createElement('P_22BNR');
            $p_22bnr->appendChild($dom->createTextNode((string) $this->p_22bnr));

            $nowySrodekTransportu->appendChild($p_22bnr);
        }

        if ($this->p_22brp instanceof P_22BRP) {
            $p_22brp = $dom->createElement('P_22BRP');
            $p_22brp->appendChild($dom->createTextNode((string) $this->p_22brp));

            $nowySrodekTransportu->appendChild($p_22brp);
        }

        /** @var DOMElement $p_22bcdgroup */
        $p_22bcdgroup = $this->p_22bcdgroup->toDom()->documentElement;

        foreach ($p_22bcdgroup->childNodes as $child) {
            $nowySrodekTransportu->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
