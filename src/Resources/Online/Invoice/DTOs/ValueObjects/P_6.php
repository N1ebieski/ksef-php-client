<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\ValueObjects;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasEvaluation;
use N1ebieski\KSEFClient\Support\Evaluation\Evaluation;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\ObjectNamespace;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

/**
 * Data dokonania lub zakończenia dostawy towarów lub wykonania usługi lub data otrzymania zapłaty, o której mowa w
 * art. 106b ust. 1 pkt 4 ustawy, o ile taka data jest określona i różni się od daty wystawienia faktury. Pole wypełnia się w
 * przypadku, gdy dla wszystkich pozycji faktury data jest wspólna
 */
final readonly class P_6 extends ValueObject implements ValueAwareInterface, Stringable
{
    public DateTimeImmutable $value;

    public function __construct(DateTimeImmutable | string $value)
    {
        $this->value = Evaluation::evaluate($value, ObjectNamespace::from(DateTimeImmutable::class));
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d');
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
