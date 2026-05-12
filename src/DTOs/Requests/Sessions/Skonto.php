<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\FromXmlArrayInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\WarunkiSkonta;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\WysokoscSkonta;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Skonto extends AbstractDTO implements DomSerializableInterface, FromXmlArrayInterface
{
    /**
     * @param WarunkiSkonta $warunkiSkonta Warunki, które nabywca powinien spełnić aby skorzystać ze skonta
     */
    public function __construct(
        public readonly WarunkiSkonta $warunkiSkonta,
        public readonly WysokoscSkonta $wysokoscSkonta
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $skonto = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Skonto');
        $dom->appendChild($skonto);

        $warunkiSkonta = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WarunkiSkonta');
        $warunkiSkonta->appendChild($dom->createTextNode((string) $this->warunkiSkonta));

        $skonto->appendChild($warunkiSkonta);

        $wysokoscSkonta = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WysokoscSkonta');
        $wysokoscSkonta->appendChild($dom->createTextNode((string) $this->wysokoscSkonta));

        $skonto->appendChild($wysokoscSkonta);

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        return Arr::onlyClassParameters($data, self::class);
    }
}
