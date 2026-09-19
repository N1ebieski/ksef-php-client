<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use RuntimeException;

final class Env
{
    public static function string(string $key): string
    {
        $value = getenv($key);

        if ($value === false) {
            throw new RuntimeException(sprintf("Environment variable [%s] is not defined.", $key));
        }

        return $value;
    }
}
