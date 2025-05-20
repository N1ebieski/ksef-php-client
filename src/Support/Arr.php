<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use Closure;

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
}
