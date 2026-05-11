<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\Sessions;

use DateTimeImmutable;
use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\OriginalInterface;
use DateTimeZone;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\Date\AfterRule;
use N1ebieski\KSEFClient\Validator\Rules\Date\BeforeRule;
use N1ebieski\KSEFClient\Validator\Rules\Date\TimezoneRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final class DataGodzZakTransportu extends AbstractValueObject implements ValueAwareInterface, Stringable, OriginalInterface
{
    public readonly DateTimeInterface $value;

    public function __construct(DateTimeInterface | string $value)
    {
        if ($value instanceof DateTimeInterface === false) {
            $value = new DateTimeImmutable($value, new DateTimeZone('UTC'));
        }

        Validator::validate($value, [
            new TimezoneRule(['UTC', 'Z']),
            new BeforeRule(new DateTimeImmutable('2050-01-01T23:59:59Z')),
            new AfterRule(new DateTimeImmutable('2021-10-01T00:00:00Z')),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d\TH:i:s\Z');
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
