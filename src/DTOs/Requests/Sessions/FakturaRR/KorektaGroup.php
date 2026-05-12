<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\DaneFaKorygowanej;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Podmiot1K;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Podmiot2K;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\TypKorekty;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrFaKorygowany;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\PrzyczynaKorekty;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class KorektaGroup extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var array<int, DaneFaKorygowanej>
     */
    public readonly array $daneFaKorygowanej;

    /**
     * @param array<int, DaneFaKorygowanej> $daneFaKorygowanej
     * @param Optional|TypKorekty $typKorekty Typ skutku korekty w ewidencji dla podatku od towarów i usług
     * @param Optional|NrFaKorygowany $nrFaKorygowany Poprawny numer faktury korygowanej w przypadku, gdy przyczyną korekty jest błędny numer faktury korygowanej. W takim przypadku błędny numer faktury należy wskazać w polu NrFaKorygowanej
     * @param Optional|Podmiot1K $podmiot1K W przypadku korekty danych sprzedawcy należy podać pełne dane sprzedawcy występujące na fakturze korygowanej. Pole nie dotyczy przypadku korekty błędnego NIP występującego na fakturze pierwotnej - wówczas wymagana jest korekta faktury do wartości zerowych
     * @param Optional|Podmiot2K $podmiot2K W przypadku korekty danych nabywcy występującego jako Podmiot2 lub dodatkowego nabywcy występującego jako Podmiot3 należy podać pełne dane tego podmiotu występujące na fakturze korygowanej. Korekcie nie podlegają błędne numery NIP identyfikujące nabywcę oraz dodatkowego nabywcę - wówczas wymagana jest korekta faktury do wartości zerowych. W przypadku korygowania pozostałych danych nabywcy lub dodatkowego nabywcy wskazany numer identyfikacyjny ma być tożsamy z numerem w części Podmiot2 względnie Podmiot3 faktury korygującej
     */
    public function __construct(
        array $daneFaKorygowanej,
        public readonly Optional | PrzyczynaKorekty $przyczynaKorekty = new Optional(),
        public readonly Optional | TypKorekty $typKorekty = new Optional(),
        public readonly Optional | NrFaKorygowany $nrFaKorygowany = new Optional(),
        public readonly Optional | Podmiot1K $podmiot1K = new Optional(),
        public readonly Optional | Podmiot2K $podmiot2K = new Optional(),
    ) {
        Validator::validate([
            'daneFaKorygowanej' => $daneFaKorygowanej,
        ], [
            'daneFaKorygowanej' => [new MinRule(1), new MaxRule(50000)],
        ]);

        $this->daneFaKorygowanej = $daneFaKorygowanej;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $korektaGroup = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'KorektaGroup');
        $dom->appendChild($korektaGroup);

        if ($this->przyczynaKorekty instanceof PrzyczynaKorekty) {
            $przyczynaKorekty = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'PrzyczynaKorekty');
            $przyczynaKorekty->appendChild($dom->createTextNode((string) $this->przyczynaKorekty));

            $korektaGroup->appendChild($przyczynaKorekty);
        }

        if ($this->typKorekty instanceof TypKorekty) {
            $typKorekty = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'TypKorekty');
            $typKorekty->appendChild($dom->createTextNode((string) $this->typKorekty->value));

            $korektaGroup->appendChild($typKorekty);
        }

        foreach ($this->daneFaKorygowanej as $daneFaKorygowanej) {
            $daneFaKorygowanej = $dom->importNode($daneFaKorygowanej->toDom()->documentElement, true);

            $korektaGroup->appendChild($daneFaKorygowanej);
        }

        if ($this->nrFaKorygowany instanceof NrFaKorygowany) {
            $nrFaKorygowany = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'NrFaKorygowany');
            $nrFaKorygowany->appendChild($dom->createTextNode((string) $this->nrFaKorygowany));

            $korektaGroup->appendChild($nrFaKorygowany);
        }

        if ($this->podmiot1K instanceof Podmiot1K) {
            $podmiot1K = $dom->importNode($this->podmiot1K->toDom()->documentElement, true);

            $korektaGroup->appendChild($podmiot1K);
        }

        if ($this->podmiot2K instanceof Podmiot2K) {
            $podmiot2K = $dom->importNode($this->podmiot2K->toDom()->documentElement, true);

            $korektaGroup->appendChild($podmiot2K);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['DaneFaKorygowanej'] = array_map(
            DaneFaKorygowanej::normalizeXmlArray(...), //@phpstan-ignore-line argument.type
            Arr::ensureList($data['DaneFaKorygowanej'])
        );

        $data['Podmiot1K'] = match (true) {
            isset($data['Podmiot1K']) => Podmiot1K::normalizeXmlArray($data['Podmiot1K']),
            default => new Optional(),
        };

        $data['Podmiot2K'] = match (true) {
            isset($data['Podmiot2K']) => Podmiot2K::normalizeXmlArray($data['Podmiot2K']),
            default => new Optional(),
        };

        return Arr::only($data, ['PrzyczynaKorekty', 'TypKorekty', 'DaneFaKorygowanej', 'NrFaKorygowany', 'Podmiot1K', 'Podmiot2K']);
    }
}
