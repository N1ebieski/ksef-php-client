<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\Attributes\ArrayOf;
use N1ebieski\KSEFClient\Support\Str;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

trait HasFromArray
{
    public static function from(array $data): static
    {
        $newParameters = [];

        $reflectionClass = new ReflectionClass(static::class);
        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return new static(); //@phpstan-ignore-line
        }

        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            $snakeName = Str::snake($parameter->getName());

            $filter = array_values(array_filter(
                array_unique([$snakeName, $parameter->getName()]),
                fn (string $value): bool => in_array($value, array_keys($data))
            ));

            if ($filter === []) {
                continue;
            }

            $name = $filter[0];

            /** @var ReflectionNamedType|null */
            $type = $parameter->getType();

            $value = match (true) {
                ($attributes = $parameter->getAttributes(ArrayOf::class)) !== [] => array_map(
                    function (mixed $item) use ($attributes): mixed {
                        $arrayOfAttribute = $attributes[0]->newInstance();

                        return match (true) {
                            is_subclass_of($arrayOfAttribute->class, FromInterface::class) => $arrayOfAttribute->class::from($item),
                            default => $item
                        };
                    },
                    $data[$name]
                ),
                default => $data[$name]
            };

            $newParameters[$parameter->getName()] = match (true) {
                $type === null => $value,
                is_subclass_of($type->getName(), FromInterface::class) => $type->getName()::from($value),
                $type->getName() === DateTimeImmutable::class => new DateTimeImmutable($value), //@phpstan-ignore-line
                default => $value
            };
        }

        return new static(...$newParameters); //@phpstan-ignore-line
    }
}
