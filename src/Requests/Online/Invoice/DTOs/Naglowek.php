<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DateTimeImmutable;
use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataWytworzeniaFa;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\SystemInfo;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\SystemCode;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Naglowek extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param null|SystemInfo $systemInfo Nazwa systemu teleinformatycznego, z którego korzysta podatnik
     * @return void
     */
    public function __construct(
        public SystemCode $wariantFormularza = SystemCode::Fa2,
        public DataWytworzeniaFa $dataWytworzeniaFa = new DataWytworzeniaFa(new DateTimeImmutable()),
        public ?SystemInfo $systemInfo = null,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $naglowek = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'Naglowek');
        $dom->appendChild($naglowek);

        $kodFormularza = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'KodFormularza');
        $kodFormularza->setAttribute('kodSystemowy', (string) $this->wariantFormularza->value);
        $kodFormularza->setAttribute('wersjaSchemy', $this->wariantFormularza->getSchemaVersion());
        $kodFormularza->appendChild($dom->createTextNode('FA'));

        $naglowek->appendChild($kodFormularza);

        $wariantFormularza = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'WariantFormularza');
        $wariantFormularza->appendChild($dom->createTextNode($this->wariantFormularza->getWariantFormularza()));

        $naglowek->appendChild($wariantFormularza);

        $dataWytworzeniaFa = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'DataWytworzeniaFa');
        $dataWytworzeniaFa->appendChild($dom->createTextNode((string) $this->dataWytworzeniaFa));

        $naglowek->appendChild($dataWytworzeniaFa);

        if ($this->systemInfo instanceof SystemInfo) {
            $systemInfo = $dom->createElementNS((string) XmlNamespace::Faktura->value, 'SystemInfo');
            $systemInfo->appendChild($dom->createTextNode((string) $this->systemInfo));
            $naglowek->appendChild($systemInfo);
        }

        return $dom;
    }
}
