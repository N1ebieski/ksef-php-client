<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

use InvalidArgumentException;

final readonly class DecimalRule extends Rule
{
    public function __construct(
        private int $min,
        private int $max
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        $fractionLength = strlen(substr(strrchr($value, '.'), 1));

        if ($fractionLength > $this->max) {
            throw new InvalidArgumentException(
                $this->getMessage(
                    sprintf('Value must have at most %d decimal places.', $this->max),
                    $attribute
                )
            );
        }

        if ($fractionLength < $this->min) {
            throw new InvalidArgumentException(
                $this->getMessage(
                    sprintf('Value must have at least %d decimal places.', $this->min),
                    $attribute
                )
            );
        }
    }
}
