<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation;

use InvalidArgumentException;
use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\ObjectNamespace;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\Type;

final readonly class Evaluation
{
    public static function evaluate(mixed $value, Type | ObjectNamespace $type): mixed
    {
        return match (true) {
            $type instanceof Type => match (true) {
                $type->isEquals(Type::Array) => match (true) {
                    is_array($value) => $value,
                    default => [$value]
                },
            },

            $type instanceof ObjectNamespace => match (true) {
                $value instanceof $type->value => $value,
                is_subclass_of($type->value, FromInterface::class) => $type->value::from($value),
                $type->value === DateTimeImmutable::class => new DateTimeImmutable($value),
                default => throw new InvalidArgumentException("Cannot convert value to {$type->value}.")
            }
        };
    }
}
