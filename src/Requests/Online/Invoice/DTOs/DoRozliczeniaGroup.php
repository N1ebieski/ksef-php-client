<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\DoRozliczenia;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class DoRozliczeniaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param DoRozliczenia $doRozliczenia Kwota nadpłacona do rozliczenia/zwrotu
     */
    public function __construct(
        public DoRozliczenia $doRozliczenia,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $doRozliczeniaGroup = $dom->createElement('DoRozliczeniaGroup');
        $dom->appendChild($doRozliczeniaGroup);

        $doRozliczenia = $dom->createElement('DoRozliczenia');
        $doRozliczenia->appendChild($dom->createTextNode((string) $this->doRozliczenia->value));

        $doRozliczeniaGroup->appendChild($doRozliczenia);

        return $dom;
    }
}
