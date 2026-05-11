<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataGodzRozpTransportu;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataGodzZakTransportu;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;

final class WysylkaGroup extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @var Optional|array<int, WysylkaPrzez>
     */
    public readonly Optional | array $wysylkaPrzez;

    /**
     * @param Optional|array<int, WysylkaPrzez> $wysylkaPrzez
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

    public static function fromXmlArray(array $data): self
    {
        $wysylkaPrzez = isset($data['wysylkaPrzez'])
            ? array_map(
                fn (array $item) => WysylkaPrzez::fromXmlArray($item),
                self::ensureList($data['wysylkaPrzez'])
            )
            : new Optional();

        return new self(
            dataGodzRozpTransportu: isset($data['dataGodzRozpTransportu'])
                ? new DataGodzRozpTransportu($data['dataGodzRozpTransportu'])
                : new Optional(),
            dataGodzZakTransportu: isset($data['dataGodzZakTransportu'])
                ? new DataGodzZakTransportu($data['dataGodzZakTransportu'])
                : new Optional(),
            wysylkaZ: isset($data['wysylkaZ']) ? WysylkaZ::fromXmlArray($data['wysylkaZ']) : new Optional(),
            wysylkaPrzez: $wysylkaPrzez,
            wysylkaDo: isset($data['wysylkaDo']) ? WysylkaDo::fromXmlArray($data['wysylkaDo']) : new Optional(),
        );
    }
}
