<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Number;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final readonly class MaxRule extends AbstractRule
{
    public function __construct(
        private float $max
    ) {
    }

    public function handle(float $value, ?string $attribute = null): void
    {
        if ($value > $this->max) {
            throw new InvalidArgumentException(
                $this->getMessage(
                    sprintf('Value must have at most %d.', $this->max),
                    $attribute
                )
            );
        }
    }
}
