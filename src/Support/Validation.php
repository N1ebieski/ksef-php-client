<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

final class Validation
{
    public static function float(float $value, int $totalDigits, int $decimalDigits, string $regex): bool
    {
        if (in_array(preg_match($regex, (string) $value), [0, false])) {
            return false;
        }

        $fractionLength = strlen(substr(strrchr((string) $value, '.'), 1));

        if ($fractionLength > $decimalDigits) {
            return false;
        }

        $length = mb_strlen(str_replace('.', '', (string) $value));

        if ($length > $totalDigits) {
            return false;
        }

        return true;
    }
}
