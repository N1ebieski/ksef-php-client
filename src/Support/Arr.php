<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use Closure;
use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\OriginalInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

final class Arr
{
    /**
     * @param array<string, mixed> $array
     * @return array<string, mixed> $array
     */
    public static function filterRecursive(array $array, Closure $closure): array
    {
        $filtered = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                //@phpstan-ignore-next-line
                $value = self::filterRecursive($value, $closure);

                if ($value === []) {
                    continue;
                }
            }

            if ( ! is_array($value) && ! $closure($value)) {
                continue;
            }

            $filtered[$key] = $value;
        }

        return $filtered;
    }

    /**
     * @param array<string|int, mixed> $array
     * @return array<string|int, mixed>
     */
    public static function toArray(array $array, KeyType $keyType = KeyType::Snake): array
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            if ($value instanceof Optional) {
                continue;
            }

            $name = is_string($key) ? match ($keyType) {
                KeyType::Camel => Str::camel($key),
                KeyType::Snake => Str::snake($key)
            } : $key;

            $newArray[$name] = match (true) {
                is_array($value) => self::toArray($value, $keyType),
                $value instanceof ArrayableInterface => $value->toArray($keyType),
                $value instanceof OriginalInterface => $value->toOriginal(),
                $value instanceof ValueAwareInterface => $value->value,
                $value instanceof DateTimeInterface => $value->format('Y-m-d\TH:i:s'),
                default => $value
            };
        }

        return $newArray;
    }

    /**
     * @param array<string, mixed> $array
     * @return array<string|int, mixed>
     */
    public static function toBody(array $array, KeyType $keyType = KeyType::Camel): array
    {
        $toArray = self::toArray($array, $keyType);

        $newArray = [];

        foreach ($array as $key => $value) {
            $name = match ($keyType) {
                KeyType::Camel => Str::camel($key),
                KeyType::Snake => Str::snake($key)
            };

            if ($value instanceof BodyInterface) {
                $newArray[$name] = $value->toBody($keyType);
            }
        }

        return array_merge($toArray, $newArray);
    }
}
