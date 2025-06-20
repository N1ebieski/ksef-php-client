<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DateTimeImmutable;
use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataWytworzeniaFa;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\SystemInfo;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\SystemCode;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Naglowek extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Optional|SystemInfo $systemInfo Nazwa systemu teleinformatycznego, z którego korzysta podatnik
     * @return void
     */
    public function __construct(
        public SystemCode $wariantFormularza = SystemCode::Fa2,
        public DataWytworzeniaFa $dataWytworzeniaFa = new DataWytworzeniaFa(new DateTimeImmutable()),
        public Optional | SystemInfo $systemInfo = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $naglowek = $dom->createElement('Naglowek');
        $dom->appendChild($naglowek);

        $kodFormularza = $dom->createElement('KodFormularza');
        $kodFormularza->setAttribute('kodSystemowy', (string) $this->wariantFormularza->value);
        $kodFormularza->setAttribute('wersjaSchemy', $this->wariantFormularza->getSchemaVersion());
        $kodFormularza->appendChild($dom->createTextNode('FA'));

        $naglowek->appendChild($kodFormularza);

        $wariantFormularza = $dom->createElement('WariantFormularza');
        $wariantFormularza->appendChild($dom->createTextNode($this->wariantFormularza->getWariantFormularza()));

        $naglowek->appendChild($wariantFormularza);

        $dataWytworzeniaFa = $dom->createElement('DataWytworzeniaFa');
        $dataWytworzeniaFa->appendChild($dom->createTextNode((string) $this->dataWytworzeniaFa));

        $naglowek->appendChild($dataWytworzeniaFa);

        if ($this->systemInfo instanceof SystemInfo) {
            $systemInfo = $dom->createElement('SystemInfo');
            $systemInfo->appendChild($dom->createTextNode((string) $this->systemInfo));
            $naglowek->appendChild($systemInfo);
        }

        return $dom;
    }
}
