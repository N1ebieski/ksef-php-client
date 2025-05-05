<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

final readonly class MaxRule extends Rule
{
    public function __construct(
        private int $max
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        if (mb_strlen($value) > $this->max) {
            throw new \InvalidArgumentException(
                $this->getMessage(
                    sprintf('Value must have at most %d characters.', $this->max),
                    $attribute
                )
            );
        }
    }
}
