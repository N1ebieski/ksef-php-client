<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Contracts\FromInterface;

trait HasFromArray
{
    public static function from(array $data): self
    {
        $parameters = [];

        $reflectionClass = new \ReflectionClass(self::class);
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            $snakeName = self::camelToSnake($parameter->getName());

            if ( ! array_key_exists($snakeName, $data)) {
                continue;
            }

            $type = $parameter->getType();

            $parameters[$parameter->getName()] = match (true) {
                $parameter->hasType() && is_subclass_of($type->getName(), FromInterface::class) => $type->getName()::from($data[$snakeName]),
                default => $data[$snakeName]
            };
        }

        return new self(...$parameters);
    }

    private static function camelToSnake(string $string): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }
}
