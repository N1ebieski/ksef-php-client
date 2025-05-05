<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

final readonly class UrlRule extends Rule
{
    public function handle(string $value, ?string $attribute = null): void
    {
        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException(
                $this->getMessage('Invalid url format.', $attribute)
            );
        }
    }
}
