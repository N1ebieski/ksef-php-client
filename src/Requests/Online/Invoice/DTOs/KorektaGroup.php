<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use DOMElement;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\NrFaKorygowany;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\OkresFaKorygowanej;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\PrzyczynaKorekty;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\TypKorekty;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class KorektaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param array<int, DaneFaKorygowanej> $daneFaKorygowanej
     * @param Optional|TypKorekty $typKorekty Typ skutku korekty w ewidencji dla podatku od towarów i usług
     * @param Optional|OkresFaKorygowanej $okresFaKorygowanej Dla faktury korygującej, o której mowa w art. 106j ust. 3 ustawy - okres, do którego odnosi się udzielany opust lub udzielana obniżka, w przypadku gdy podatnik udziela opustu lub obniżki ceny w odniesieniu do dostaw towarów lub usług dokonanych lub świadczonych na rzecz jednego odbiorcy w danym okresie
     * @param Optional|NrFaKorygowany $nrFaKorygowany Poprawny numer faktury korygowanej w przypadku, gdy przyczyną korekty jest błędny numer faktury korygowanej. W takim przypadku błędny numer faktury należy wskazać w polu NrFaKorygowanej
     * @param Optional|Podmiot1K $podmiot1K W przypadku korekty danych sprzedawcy należy podać pełne dane sprzedawcy występujące na fakturze korygowanej. Pole nie dotyczy przypadku korekty błędnego NIP występującego na fakturze pierwotnej - wówczas wymagana jest korekta faktury do wartości zerowych
     * @param Optional|array<int, Podmiot2K> $podmiot2K W przypadku korekty danych nabywcy występującego jako Podmiot2 lub dodatkowego nabywcy występującego jako Podmiot3 należy podać pełne dane tego podmiotu występujące na fakturze korygowanej. Korekcie nie podlegają błędne numery identyfikujące nabywcę oraz dodatkowego nabywcę. W przypadku korygowania pozostałych danych nabywcy lub dodatkowego nabywcy wskazany numer identyfikacyjny ma być tożsamy z numerem w części Podmiot2 względnie Podmiot3 faktury korygującej
     */
    public function __construct(
        public array $daneFaKorygowanej,
        public Optional | PrzyczynaKorekty $przyczynaKorekty = new Optional(),
        public Optional | TypKorekty $typKorekty = new Optional(),
        public Optional | OkresFaKorygowanej $okresFaKorygowanej = new Optional(),
        public Optional | NrFaKorygowany $nrFaKorygowany = new Optional(),
        public Optional | Podmiot1K $podmiot1K = new Optional(),
        public Optional | array $podmiot2K = new Optional(),
        public Optional | P_15ZKGroup $p15ZKGroup = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $korektaGroup = $dom->createElement('KorektaGroup');
        $dom->appendChild($korektaGroup);

        if ($this->przyczynaKorekty instanceof PrzyczynaKorekty) {
            $przyczynaKorekty = $dom->createElement('PrzyczynaKorekty');
            $przyczynaKorekty->appendChild($dom->createTextNode((string) $this->przyczynaKorekty));

            $korektaGroup->appendChild($przyczynaKorekty);
        }

        if ($this->typKorekty instanceof TypKorekty) {
            $typKorekty = $dom->createElement('TypKorekty');
            $typKorekty->appendChild($dom->createTextNode((string) $this->typKorekty->value));

            $korektaGroup->appendChild($typKorekty);
        }

        foreach ($this->daneFaKorygowanej as $daneFaKorygowanej) {
            $daneFaKorygowanej = $dom->importNode($daneFaKorygowanej->toDom()->documentElement, true);

            $korektaGroup->appendChild($daneFaKorygowanej);
        }

        if ($this->okresFaKorygowanej instanceof OkresFaKorygowanej) {
            $okresFaKorygowanej = $dom->createElement('OkresFaKorygowanej');
            $okresFaKorygowanej->appendChild($dom->createTextNode((string) $this->okresFaKorygowanej));

            $korektaGroup->appendChild($okresFaKorygowanej);
        }

        if ($this->nrFaKorygowany instanceof NrFaKorygowany) {
            $nrFaKorygowany = $dom->createElement('NrFaKorygowany');
            $nrFaKorygowany->appendChild($dom->createTextNode((string) $this->nrFaKorygowany));

            $korektaGroup->appendChild($nrFaKorygowany);
        }

        if ($this->podmiot1K instanceof Podmiot1K) {
            $podmiot1K = $dom->importNode($this->podmiot1K->toDom()->documentElement, true);

            $korektaGroup->appendChild($podmiot1K);
        }

        if ( ! $this->podmiot2K instanceof Optional) {
            foreach ($this->podmiot2K as $podmiot2K) {
                $podmiot2K = $dom->importNode($podmiot2K->toDom()->documentElement, true);

                $korektaGroup->appendChild($podmiot2K);
            }
        }

        if ($this->p15ZKGroup instanceof P_15ZKGroup) {
            /** @var DOMElement $p15ZKGroup */
            $p15ZKGroup = $this->p15ZKGroup->toDom()->documentElement;

            foreach ($p15ZKGroup->childNodes as $child) {
                $korektaGroup->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }
}
