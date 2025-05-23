<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrEORI;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\RolaPU;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class PodmiotUpowazniony extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param PodmiotUpowaznionyDaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podmiotu upoważnionego
     * @param Adres $adres Adres podmiotu upoważnionego
     * @param NrEORI|Optional $nrEORI Numer EORI podmiotu upoważnionego
     * @param Optional|array<int, PodmiotUpowaznionyDaneKontaktowe> $daneKontaktowe Dane kontaktowe podmiotu upoważnionego
     * @param RolaPU $rolaPU Rola podmiotu upoważnionego
     */
    public function __construct(
        public PodmiotUpowaznionyDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public Adres $adres,
        public RolaPU $rolaPU,
        public Optional | NrEORI $nrEORI = new Optional(),
        public Optional | AdresKoresp $adresKoresp = new Optional(),
        public Optional|array $daneKontaktowe = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiotUpowazniony = $dom->createElement('PodmiotUpowazniony');
        $dom->appendChild($podmiotUpowazniony);

        if ($this->nrEORI instanceof NrEORI) {
            $nrEORI = $dom->createElement('NrEORI');
            $nrEORI->appendChild($dom->createTextNode((string) $this->nrEORI));
            $podmiotUpowazniony->appendChild($nrEORI);
        }

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiotUpowazniony->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiotUpowazniony->appendChild($adres);

        if ($this->adresKoresp instanceof AdresKoresp) {
            $adresKoresp = $dom->importNode($this->adresKoresp->toDom()->documentElement, true);

            $podmiotUpowazniony->appendChild($adresKoresp);
        }

        if ( ! $this->daneKontaktowe instanceof Optional) {
            foreach ($this->daneKontaktowe as $daneKontaktowe) {
                $daneKontaktowe = $dom->importNode($daneKontaktowe->toDom()->documentElement, true);

                $podmiotUpowazniony->appendChild($daneKontaktowe);
            }
        }

        $rolaPU = $dom->createElement('RolaPU');
        $rolaPU->appendChild($dom->createTextNode((string) $this->rolaPU->value));

        $podmiotUpowazniony->appendChild($rolaPU);

        return $dom;
    }
}
