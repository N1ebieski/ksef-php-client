<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\Str;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

trait HasFromArray
{
    public static function from(array $data): static
    {
        $attributes = [];

        $reflectionClass = new ReflectionClass(static::class);
        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return new static(); //@phpstan-ignore-line
        }

        $parameters = $constructor->getParameters();
        $parametersAsArray = array_map(fn (ReflectionParameter $parameter): string => $parameter->getName(), $parameters);

        foreach ($parameters as $parameter) {
            $snakeName = Str::snake($parameter->getName());

            $filter = array_values(array_filter(
                array_unique([$snakeName, $parameter->getName()]),
                fn (string $value): bool => in_array($value, $parametersAsArray)
            ));

            if ($filter === []) {
                continue;
            }

            $name = $filter[0];

            /** @var ReflectionNamedType|null */
            $type = $parameter->getType();

            $attributes[$parameter->getName()] = match (true) {
                $type === null => $data[$name],
                is_subclass_of($type->getName(), FromInterface::class) => $type->getName()::from($data[$name]),
                $type->getName() === DateTimeImmutable::class => new DateTimeImmutable($data[$name]), //@phpstan-ignore-line
                default => $data[$name]
            };
        }

        return new static(...$attributes); //@phpstan-ignore-line
    }
}
