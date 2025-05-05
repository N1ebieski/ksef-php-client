<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

use InvalidArgumentException;

final readonly class MaxDigits extends Rule
{
    public function __construct(
        private int $max
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        $length = strlen(str_replace('.', '', $value));

        if ($length > $this->max) {
            throw new InvalidArgumentException(
                $this->getMessage(
                    sprintf('Value must have at most %d digits.', $this->max),
                    $attribute
                )
            );
        }
    }
}
