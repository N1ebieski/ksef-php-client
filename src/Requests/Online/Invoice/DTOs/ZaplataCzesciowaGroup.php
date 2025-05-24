<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\ZnacznikZaplatyCzesciowej;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class ZaplataCzesciowaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var Optional|array<int, ZaplataCzesciowa>
     */
    public Optional | array $zaplataCzesciowa;

    /**
     * @param Optional|array<int, ZaplataCzesciowa> $zaplataCzesciowa Dane zapłat częściowych
     * @param ZnacznikZaplatyCzesciowej $znacznikZaplatyCzesciowej Znacznik informujący, że kwota należności wynikająca z faktury została zapłacona w części: 1 - zapłacono w części
     */
    public function __construct(
        Optional | array $zaplataCzesciowa = new Optional(),
        public ZnacznikZaplatyCzesciowej $znacznikZaplatyCzesciowej = ZnacznikZaplatyCzesciowej::Default
    ) {
        Validator::validate([
            'zaplataCzesciowa' => $zaplataCzesciowa,
        ], [
            'zaplataCzesciowa' => [new MinRule(1), new MaxRule(100)],
        ]);

        $this->zaplataCzesciowa = $zaplataCzesciowa;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataCzesciowaGroup = $dom->createElement('ZaplataCzesciowaGroup');
        $dom->appendChild($zaplataCzesciowaGroup);

        $znacznikZaplatyCzesciowej = $dom->createElement('ZnacznikZaplatyCzesciowej');
        $znacznikZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->znacznikZaplatyCzesciowej->value));

        $zaplataCzesciowaGroup->appendChild($znacznikZaplatyCzesciowej);

        if ( ! $this->zaplataCzesciowa instanceof Optional) {
            foreach ($this->zaplataCzesciowa as $zaplataCzesciowa) {
                $zaplataCzesciowa = $dom->importNode($zaplataCzesciowa->toDom()->documentElement, true);

                $zaplataCzesciowaGroup->appendChild($zaplataCzesciowa);
            }
        }

        return $dom;
    }
}
