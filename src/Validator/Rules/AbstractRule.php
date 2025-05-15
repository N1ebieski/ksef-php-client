<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

/**
 * @method void handle(mixed $value, ?string $attribute = null)
 */
abstract readonly class AbstractRule
{
    public function getMessage(string $message, ?string $attribute = null): string
    {
        if ($attribute !== null) {
            $pos = strrpos($message, '.');
            $replacement = " for attribute {$attribute}.";

            return match (true) {
                $pos === false => $message . $replacement,
                default => substr_replace($message, $replacement, $pos, 1),
            };
        }

        return $message;
    }
}
