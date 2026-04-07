<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

final class Str
{
    public static function isBinary(mixed $value): bool
    {
        if ( ! is_string($value)) {
            return false;
        }

        if (mb_check_encoding($value, 'UTF-8') === false) {
            return true;
        }

        return (bool) preg_match('/[\x00-\x08\x0E-\x1F]/', $value);
    }

    public static function isJson(string $value): bool
    {
        json_decode($value);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function base64URLEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    public static function base64URLDecode(string $value): string
    {
        $padding = 4 - (strlen($value) % 4);

        if ($padding !== 4) {
            $value .= str_repeat('=', $padding);
        }

        return base64_decode(strtr($value, '-_', '+/'));
    }

    public static function snake(string $value): string
    {
        /** @var string $replace */
        $replace = preg_replace('/([a-z])([A-Z])/', '$1_$2', $value);

        return strtolower($replace);
    }

    public static function camel(string $value): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $value))));
    }

    public static function guid(): string
    {
        return str_replace('.', '', uniqid('ID-', true));
    }
}
