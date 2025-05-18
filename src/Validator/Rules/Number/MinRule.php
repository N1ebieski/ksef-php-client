<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Number;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final readonly class MinRule extends AbstractRule
{
    public function __construct(
        private float $min
    ) {
    }

    public function handle(float $value, ?string $attribute = null): void
    {
        if ($value < $this->min) {
            throw new InvalidArgumentException(
                $this->getMessage(
                    sprintf('Value must have at least %d.', $this->min),
                    $attribute
                )
            );
        }
    }
}
