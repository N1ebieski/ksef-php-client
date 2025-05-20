<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use Closure;

final class Arr
{
    public static function filterRecursive(array $array, Closure $closure): array
    {
        $filtered = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
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
