<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects;

use DateTimeInterface;
use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\Date\AfterRule;
use N1ebieski\KSEFClient\Validator\Rules\Date\BeforeRule;
use N1ebieski\KSEFClient\Validator\Rules\String\RegexRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class P_6_Do extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public DateTimeInterface $value;

    public function __construct(DateTimeInterface | string $value)
    {
        if ($value instanceof \DateTimeInterface === false) {
            $value = new DateTimeImmutable($value);
        }

        Validator::validate($value, [
            new BeforeRule(new DateTimeImmutable('2050-01-01')),
            new AfterRule(new DateTimeImmutable('2006-01-01')),
            new RegexRule('/((\d{4})-(\d{2})-(\d{2}))/')
        ]);

        $this->value = $value;
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
