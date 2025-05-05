<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

use InvalidArgumentException;

final readonly class EmailRule extends Rule
{
    public function handle(string $value, ?string $attribute = null): void
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException(
                $this->getMessage('Invalid email format.', $attribute)
            );
        }
    }
}
