<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataGodzRozpTransportu;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataGodzZakTransportu;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class WysylkaGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, WysylkaPrzez>
     */
    public readonly Optional | array $wysylkaPrzez;

    /**
     * @param Optional|DataGodzRozpTransportu $dataGodzRozpTransportu Data i godzina rozpoczęcia transportu
     * @param Optional|DataGodzZakTransportu $dataGodzZakTransportu Data i godzina zakonczenia transportu
     * @param Optional|WysylkaZ $wysylkaZ Adres miejsca wysyłki
     * @param Optional|array<int, WysylkaPrzez> $wysylkaPrzez Adres pośredni wysyłki
     * @param Optional|WysylkaDo $wysylkaDo Adres miejsca docelowego, do którego został zlecony transport
     */
    public function __construct(
        public readonly Optional | DataGodzRozpTransportu $dataGodzRozpTransportu = new Optional(),
        public readonly Optional | DataGodzZakTransportu $dataGodzZakTransportu = new Optional(),
        public readonly Optional | WysylkaZ $wysylkaZ = new Optional(),
        Optional | array $wysylkaPrzez = new Optional(),
        public readonly Optional | WysylkaDo $wysylkaDo = new Optional(),
    ) {
        Validator::validate([
            'wysylkaPrzez' => $wysylkaPrzez
        ], [
            'wysylkaPrzez' => [new MaxRule(20)]
        ]);

        $this->wysylkaPrzez = $wysylkaPrzez;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $wysylkaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WysylkaGroup');
        $dom->appendChild($wysylkaGroup);

        if ($this->dataGodzRozpTransportu instanceof DataGodzRozpTransportu) {
            $dataGodzRozpTransportu = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataGodzRozpTransportu');
            $dataGodzRozpTransportu->appendChild($dom->createTextNode((string) $this->dataGodzRozpTransportu));

            $wysylkaGroup->appendChild($dataGodzRozpTransportu);
        }

        if ($this->dataGodzZakTransportu instanceof DataGodzZakTransportu) {
            $dataGodzZakTransportu = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataGodzZakTransportu');
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

    public static function normalizeXmlArray(array $data): array
    {
        $data['WysylkaPrzez'] = match (true) {
            isset($data['WysylkaPrzez']) => array_map(
                WysylkaPrzez::normalizeXmlArray(...),
                Arr::ensureList($data['WysylkaPrzez'])
            ),
            default => new Optional(),
        };

        $data['WysylkaZ'] = match (true) {
            isset($data['WysylkaZ']) => WysylkaZ::normalizeXmlArray($data['WysylkaZ']),
            default => new Optional(),
        };

        $data['WysylkaDo'] = match (true) {
            isset($data['WysylkaDo']) => WysylkaDo::normalizeXmlArray($data['WysylkaDo']),
            default => new Optional(),
        };

        return Arr::only($data, ['DataGodzRozpTransportu', 'DataGodzZakTransportu', 'WysylkaPrzez', 'WysylkaZ', 'WysylkaDo']);
    }
}
