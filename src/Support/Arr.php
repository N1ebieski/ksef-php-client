<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use Closure;
use DateTimeInterface;
use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\OriginalInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;
use ReflectionClass;

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
     * @param array<int, string> $keyTypeExcept
     * @param array<int, string> $only
     * @return array<string|int, mixed>
     */
    public static function normalize(
        array $array,
        KeyType $keyType = KeyType::Camel,
        array $keyTypeExcept = [],
        array $only = []
    ): array {
        $newArray = [];

        if ($only !== []) {
            $array = self::only($array, $only);
        }

        foreach ($array as $key => $value) {
            if ($value instanceof Optional) {
                continue;
            }

            $name = is_string($key) && (array_filter($keyTypeExcept, fn (string $except): bool => str_starts_with($key, $except)) === [])
                ? match ($keyType) {
                    KeyType::Camel => Str::camel($key),
                    KeyType::Snake => Str::snake($key)
                } : $key;

            $newArray[$name] = match (true) {
                is_array($value) => self::normalize($value, $keyType),
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
     * @param array<int, string> $only
     * @return array<string, mixed>
     */
    public static function only(array $array, array $only): array
    {
        return array_intersect_key($array, array_flip($only));
    }

    /**
     * Wraps a single XML-parsed item (scalar or associative array) into a list.
     * When SimpleXML parses a single repeated element, json_encode returns a scalar
     * or associative array instead of an indexed array.
     */
    public static function ensureList(mixed $data): array
    {
        if ( ! is_array($data)) {
            return [$data];
        }

        return array_is_list($data) ? $data : [$data];
    }
}
