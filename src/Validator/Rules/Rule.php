<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

abstract readonly class Rule
{
    public function getMessage(string $message, ?string $attribute = null): string
    {
        $pos = strrpos($message, '.');

        if ($attribute !== null) {
            $replacement = " for attribute {$attribute}.";

            return match (true) {
                $pos === false => $message . $replacement,
                default => substr_replace($message, $replacement, $pos, 1),
            };
        }

        return $message;
    }

    abstract public function handle(string $value, ?string $attribute = null): void;
}
