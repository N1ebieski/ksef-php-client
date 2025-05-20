<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\Str;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

trait HasToArray
{
    public function toArray(?KeyType $keyType = KeyType::Snake): array
    {
        $parameters = get_object_vars($this);
        $newParameters = [];

        foreach ($parameters as $key => $value) {
            $name = match ($keyType) {
                KeyType::Camel => Str::camel($key),
                KeyType::Snake => Str::snake($key)
            };

            $newParameters[$name] = match (true) {
                $value instanceof ArrayableInterface => $value->toArray($keyType),
                $value instanceof ValueAwareInterface => $value->value,
                $value instanceof DateTimeImmutable => $value->format('Y-m-d\TH:i:s'),
                default => $value
            };
        }

        return $newParameters;
    }
}
