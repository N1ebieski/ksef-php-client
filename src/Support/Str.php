<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

final class Str
{
    public static function snake(string $string): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }
}
