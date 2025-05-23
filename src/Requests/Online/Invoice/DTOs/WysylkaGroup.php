<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataGodzRozpTransportu;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataGodzZakTransportu;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class WysylkaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Optional|DataGodzRozpTransportu $dataGodzRozpTransportu Data i godzina rozpoczęcia transportu
     * @param Optional|DataGodzZakTransportu $dataGodzZakTransportu Data i godzina zakonczenia transportu
     * @param Optional|WysylkaZ $wysylkaZ Adres miejsca wysyłki
     * @param Optional|array<int, WysylkaPrzez> $wysylkaPrzez Adres pośredni wysyłki
     * @param Optional|WysylkaDo $wysylkaDo Adres miejsca docelowego, do którego został zlecony transport
     */
    public function __construct(
        public Optional | DataGodzRozpTransportu $dataGodzRozpTransportu = new Optional(),
        public Optional | DataGodzZakTransportu $dataGodzZakTransportu = new Optional(),
        public Optional | WysylkaZ $wysylkaZ = new Optional(),
        public Optional | array $wysylkaPrzez = new Optional(),
        public Optional | WysylkaDo $wysylkaDo = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $wysylkaGroup = $dom->createElement('WysylkaGroup');
        $dom->appendChild($wysylkaGroup);

        if ($this->dataGodzRozpTransportu instanceof DataGodzRozpTransportu) {
            $dataGodzRozpTransportu = $dom->createElement('DataGodzRozpTransportu');
            $dataGodzRozpTransportu->appendChild($dom->createTextNode((string) $this->dataGodzRozpTransportu));

            $wysylkaGroup->appendChild($dataGodzRozpTransportu);
        }

        if ($this->dataGodzZakTransportu instanceof DataGodzZakTransportu) {
            $dataGodzZakTransportu = $dom->createElement('DataGodzZakTransportu');
            $dataGodzZakTransportu->appendChild($dom->createTextNode((string) $this->dataGodzZakTransportu));

            $wysylkaGroup->appendChild($dataGodzZakTransportu);
        }

        if ($this->wysylkaZ instanceof WysylkaZ) {
            $wysylkaZ = $dom->importNode($this->wysylkaZ->toDom()->documentElement, true);

            $wysylkaGroup->appendChild($wysylkaZ);
        }

        if ( ! $this->wysylkaPrzez instanceof Optional) {
            foreach ($this->wysylkaPrzez as $wysylkaPrzez) {
                $wysylkaPrzez = $dom->importNode($wysylkaPrzez->toDom()->documentElement, true);

                $wysylkaGroup->appendChild($wysylkaPrzez);
            }
        }

        if ($this->wysylkaDo instanceof WysylkaDo) {
            $wysylkaDo = $dom->importNode($this->wysylkaDo->toDom()->documentElement, true);

            $wysylkaGroup->appendChild($wysylkaDo);
        }

        return $dom;
    }
}
