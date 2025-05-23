<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Termin;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\TerminOpis;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class TerminPlatnosci extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Termin $termin Termin płatności
     * @param Optional|TerminOpis $terminOpis Opis terminu płatności
     */
    public function __construct(
        public Termin $termin,
        public Optional | TerminOpis $terminOpis = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $terminPlatnosci = $dom->createElement('TerminPlatnosci');
        $dom->appendChild($terminPlatnosci);

        $termin = $dom->createElement('Termin');
        $termin->appendChild($dom->createTextNode((string) $this->termin));

        $terminPlatnosci->appendChild($termin);

        if ($this->terminOpis instanceof TerminOpis) {
            $terminOpis = $dom->createElement('TerminOpis');
            $terminOpis->appendChild($dom->createTextNode((string) $this->terminOpis));

            $terminPlatnosci->appendChild($terminOpis);
        }

        return $dom;
    }
}
