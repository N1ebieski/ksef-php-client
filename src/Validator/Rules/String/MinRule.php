<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\String;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\Rule;

final readonly class MinRule extends Rule
{
    public function __construct(
        private int $min
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        if (mb_strlen($value) < $this->min) {
            throw new InvalidArgumentException(
                $this->getMessage(
                    sprintf('Value must have at least %d characters.', $this->min),
                    $attribute
                )
            );
        }
    }
}
