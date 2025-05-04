<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\Str;

trait HasFromArray
{
    public static function from(array $data): static
    {
        $parameters = [];

        $reflectionClass = new \ReflectionClass(static::class);
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            $snakeName = Str::snake($parameter->getName());

            if ( ! array_key_exists($snakeName, $data)) {
                continue;
            }

            /** @var \ReflectionNamedType|null */
            $type = $parameter->getType();

            $parameters[$parameter->getName()] = match (true) {
                $type !== null && is_subclass_of($type->getName(), FromInterface::class) => $type->getName()::from($data[$snakeName]),
                default => $data[$snakeName]
            };
        }

        return new static(...$parameters); //@phpstan-ignore-line
    }
}
