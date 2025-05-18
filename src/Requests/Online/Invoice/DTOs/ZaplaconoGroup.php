<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DataZaplaty;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\Zaplacono;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class ZaplaconoGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Zaplacono $zaplacono Znacznik informujący, że kwota należności wynikająca z faktury została zapłacona: 1 - zapłacono
     * @param DataZaplaty $dataZaplaty Data zapłaty, jeśli do wystawienia faktury płatność została dokonana
     * @return void
     */
    public function __construct(
        public DataZaplaty $dataZaplaty,
        public Zaplacono $zaplacono = Zaplacono::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplaconoGroup = $dom->createElement('ZaplaconoGroup');
        $dom->appendChild($zaplaconoGroup);

        $zaplacono = $dom->createElement('Zaplacono');
        $zaplacono->appendChild($dom->createTextNode((string) $this->zaplacono->value));

        $zaplaconoGroup->appendChild($zaplacono);

        $dataZaplaty = $dom->createElement('DataZaplaty');
        $dataZaplaty->appendChild($dom->createTextNode((string) $this->dataZaplaty));

        $zaplaconoGroup->appendChild($dataZaplaty);

        return $dom;
    }
}
