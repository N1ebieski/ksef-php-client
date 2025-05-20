<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use Symfony\Component\Uid\Uuid;

final class Str
{
    public static function snake(string $string): string
    {
        /** @var string $replace */
        $replace = preg_replace('/([a-z])([A-Z])/', '$1_$2', $string);

        return strtolower($replace);
    }

    public static function camel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    public static function guid(): string
    {
        return 'ID-' . Uuid::v4();
    }
}
