<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\FormaPlatnosciGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\PlatnoscInnaGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\RachunekBankowy1;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\IPKSeF;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\LinkDoPlatnosci;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Platnosc extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var Optional|array<int, RachunekBankowy1>
     */
    public readonly Optional | array $rachunekBankowy1;

    /**
     * @var Optional|array<int, RachunekBankowy2>
     */
    public readonly Optional | array $rachunekBankowy2;

    /**
     * @param Optional|array<int, RachunekBankowy1> $rachunekBankowy1 Dane rachunku bankowego rolnika ryczałtowego
     * @param Optional|array<int, RachunekBankowy2> $rachunekBankowy2 Dane rachunku bankowego nabywcy – podatnika VAT czynnego
     * @param Optional|IPKSeF $ipksef Identyfikator płatności KSeF
     * @param Optional|LinkDoPlatnosci $linkDoPlatnosci Link do płatności bezgotówkowej
     */
    public function __construct(
        public readonly Optional | FormaPlatnosciGroup | PlatnoscInnaGroup $platnoscGroup = new Optional(),
        Optional | array $rachunekBankowy1 = new Optional(),
        Optional | array $rachunekBankowy2 = new Optional(),
        public readonly Optional | IPKSeF $ipksef = new Optional(),
        public readonly Optional | LinkDoPlatnosci $linkDoPlatnosci = new Optional()
    ) {
        Validator::validate([
            'rachunekBankowy1' => $rachunekBankowy1,
            'rachunekBankowy2' => $rachunekBankowy2
        ], [
            'rachunekBankowy1' => [new MaxRule(3)],
            'rachunekBankowy2' => [new MaxRule(3)]
        ]);

        $this->rachunekBankowy1 = $rachunekBankowy1;
        $this->rachunekBankowy2 = $rachunekBankowy2;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $platnosc = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Platnosc');
        $dom->appendChild($platnosc);

        if ( ! $this->platnoscGroup instanceof Optional) {
            /** @var DOMElement $platnoscGroup */
            $platnoscGroup = $this->platnoscGroup->toDom()->documentElement;

            foreach ($platnoscGroup->childNodes as $child) {
                $platnosc->appendChild($dom->importNode($child, true));
            }
        }

        if ( ! $this->rachunekBankowy1 instanceof Optional) {
            foreach ($this->rachunekBankowy1 as $rachunekBankowy1) {
                $rachunekBankowy1 = $dom->importNode($rachunekBankowy1->toDom()->documentElement, true);

                $platnosc->appendChild($rachunekBankowy1);
            }
        }


        if ( ! $this->rachunekBankowy2 instanceof Optional) {
            foreach ($this->rachunekBankowy2 as $rachunekBankowy2) {
                $rachunekBankowy2 = $dom->importNode($rachunekBankowy2->toDom()->documentElement, true);

                $platnosc->appendChild($rachunekBankowy2);
            }
        }

        if ($this->ipksef instanceof IPKSeF) {
            $ipksef = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'IPKSeF');
            $ipksef->appendChild($dom->createTextNode((string) $this->ipksef));
            $platnosc->appendChild($ipksef);
        }

        if ($this->linkDoPlatnosci instanceof LinkDoPlatnosci) {
            $linkDoPlatnosci = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'LinkDoPlatnosci');
            $linkDoPlatnosci->appendChild($dom->createTextNode((string) $this->linkDoPlatnosci));
            $platnosc->appendChild($linkDoPlatnosci);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['platnoscGroup'] = match (true) {
            isset($data['FormaPlatnosci']) => FormaPlatnosciGroup::normalizeXmlArray($data),
            isset($data['PlatnoscInna']) => PlatnoscInnaGroup::normalizeXmlArray($data),
            default => new Optional(),
        };

        $data['RachunekBankowy1'] = match (true) {
            isset($data['RachunekBankowy1']) => array_map(
                RachunekBankowy1::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['RachunekBankowy1'])
            ),
            default => new Optional(),
        };

        $data['RachunekBankowy2'] = match (true) {
            isset($data['RachunekBankowy2']) => array_map(
                RachunekBankowy2::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
                Arr::ensureList($data['RachunekBankowy2'])
            ),
            default => new Optional(),
        };

        $data['ipksef'] = $data['IPKSeF'] ?? new Optional();

        return Arr::only($data, ['platnoscGroup', 'RachunekBankowy1', 'RachunekBankowy2', 'ipksef', 'LinkDoPlatnosci']);
    }
}
