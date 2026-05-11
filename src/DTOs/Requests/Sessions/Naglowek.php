<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DateTimeImmutable;
use DateTimeZone;
use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataWytworzeniaFa;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\SystemInfo;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Naglowek extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param Optional|SystemInfo $systemInfo Nazwa systemu teleinformatycznego, z którego korzysta podatnik
     */
    public function __construct(
        public readonly FormCode $wariantFormularza = FormCode::Fa3,
        public readonly DataWytworzeniaFa $dataWytworzeniaFa = new DataWytworzeniaFa(new DateTimeImmutable('now', new DateTimeZone('UTC'))),
        public readonly Optional | SystemInfo $systemInfo = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $naglowek = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Naglowek');
        $dom->appendChild($naglowek);

        $kodFormularza = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KodFormularza');
        $kodFormularza->setAttribute('kodSystemowy', (string) $this->wariantFormularza->value);
        $kodFormularza->setAttribute('wersjaSchemy', $this->wariantFormularza->getSchemaVersion());
        $kodFormularza->appendChild($dom->createTextNode('FA'));

        $naglowek->appendChild($kodFormularza);

        $wariantFormularza = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WariantFormularza');
        $wariantFormularza->appendChild($dom->createTextNode($this->wariantFormularza->getWariantFormularza()));

        $naglowek->appendChild($wariantFormularza);

        $dataWytworzeniaFa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataWytworzeniaFa');
        $dataWytworzeniaFa->appendChild($dom->createTextNode((string) $this->dataWytworzeniaFa));

        $naglowek->appendChild($dataWytworzeniaFa);

        if ($this->systemInfo instanceof SystemInfo) {
            $systemInfo = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'SystemInfo');
            $systemInfo->appendChild($dom->createTextNode((string) $this->systemInfo));
            $naglowek->appendChild($systemInfo);
        }

        return $dom;
    }

    public static function fromXmlArray(array $data): self
    {
        return new self(
            wariantFormularza: FormCode::from($data['kodFormularza']['@attributes']['kodSystemowy']),
            dataWytworzeniaFa: new DataWytworzeniaFa($data['dataWytworzeniaFa']),
            systemInfo: isset($data['systemInfo']) ? new SystemInfo($data['systemInfo']) : new Optional(),
        );
    }
}
