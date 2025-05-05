<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation;

use DateTimeImmutable;
use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\Type;

final readonly class Evaluation
{
    /**
     * @param Type|class-string $type
     */
    public static function evaluate(mixed $value, Type | string $type): mixed
    {
        return match (true) {
            //@phpstan-ignore-next-line
            $type instanceof Type => match (true) {
                $type->isEquals(Type::Array) => match (true) {
                    is_array($value) => $value,
                    default => [$value]
                },
            },

            is_string($type) => match (true) {
                $value instanceof $type => $value,
                is_subclass_of($type, FromInterface::class) => $type::from($value),
                $type === DateTimeImmutable::class => new DateTimeImmutable($value), //@phpstan-ignore-line
                default => throw new InvalidArgumentException("Cannot convert value to {$type}.")
            }
        };
    }
}
