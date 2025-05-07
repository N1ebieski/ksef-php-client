<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Number;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\Rule;

final readonly class DecimalRule extends Rule
{
    public function __construct(
        private int $min,
        private int $max
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        $fraction = strrchr($value, '.');

        $fractionLength = match (true) {
            $fraction === false => 0,
            default => strlen(substr($fraction, 1)),
        };

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
