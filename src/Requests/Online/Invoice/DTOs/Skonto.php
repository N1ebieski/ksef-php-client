<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\WarunkiSkonta;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\WysokoscSkonta;
use N1ebieski\KSEFClient\Support\AbstractDTO;

final readonly class Skonto extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param WarunkiSkonta $warunkiSkonta Warunki, które nabywca powinien spełnić aby skorzystać ze skonta
     */
    public function __construct(
        public WarunkiSkonta $warunkiSkonta,
        public WysokoscSkonta $wysokoscSkonta
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $skonto = $dom->createElement('Skonto');
        $dom->appendChild($skonto);

        $warunkiSkonta = $dom->createElement('WarunkiSkonta');
        $warunkiSkonta->appendChild($dom->createTextNode((string) $this->warunkiSkonta));

        $skonto->appendChild($warunkiSkonta);

        $wysokoscSkonta = $dom->createElement('WysokoscSkonta');
        $wysokoscSkonta->appendChild($dom->createTextNode((string) $this->wysokoscSkonta));

        $skonto->appendChild($wysokoscSkonta);

        return $dom;
    }
}
