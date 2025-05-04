<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

final class Str
{
    public static function snake(string $string): string
    {
        /** @var string $replace */
        $replace = preg_replace('/([a-z])([A-Z])/', '$1_$2', $string);

        return strtolower($replace);
    }
}
