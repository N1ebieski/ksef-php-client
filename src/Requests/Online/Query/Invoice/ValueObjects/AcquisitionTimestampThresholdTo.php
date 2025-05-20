<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\OriginalInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\Date\BeforeRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class AcquisitionTimestampThresholdTo extends AbstractValueObject implements ValueAwareInterface, Stringable, OriginalInterface
{
    public DateTimeInterface $value;

    public function __construct(DateTimeInterface | string $value)
    {
        if ($value instanceof DateTimeInterface === false) {
            $value = new DateTimeImmutable($value);
        }

        Validator::validate($value, [
            new BeforeRule(new DateTimeImmutable('now')->modify('+6 hours')),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d\TH:i:s');
    }

    public function toOriginal(): string
    {
        return (string) $this;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
