<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasEvaluation;
use N1ebieski\KSEFClient\Support\Validation;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\DecimalRule;
use N1ebieski\KSEFClient\Validator\Rules\MaxDigits;
use N1ebieski\KSEFClient\Validator\Rules\RegexRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

/**
 * Kwota podatku od sumy wartości sprzedaży netto objętej stawką obniżoną drugą - aktualnie 5%. W przypadku faktur
 * zaliczkowych, kwota podatku wyliczona według wzoru, o którym mowa w art. 106f ust. 1 pkt 3 ustawy. W przypadku
 * faktur korygujących, kwota różnicy, o której mowa w art. 106j ust. 2 pkt 5 ustawy
 */
final readonly class P_14_3 extends ValueObject implements ValueAwareInterface, Stringable
{
    public float $value;

    public function __construct(float $value)
    {
        Validator::validate((string) $value, [
            new RegexRule('/-?([1-9]\d{0,15}|0)(\.\d{1,2})?/'),
            new DecimalRule(0, 2),
            new MaxDigits(18),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return number_format($this->value, 2, '.', '');
    }

    public static function from(float $value): self
    {
        return new self($value);
    }
}
