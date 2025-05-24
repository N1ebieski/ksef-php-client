<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Array;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final readonly class MaxRule extends AbstractRule
{
    public function __construct(
        private int $max
    ) {
    }

    /**
     * @param array<int, mixed> $value
     */
    public function handle(array $value, ?string $attribute = null): void
    {
        if (count($value) > $this->max) {
            throw new InvalidArgumentException(
                $this->getMessage(
                    sprintf('Value must have at most %d elements.', $this->max),
                    $attribute
                )
            );
        }
    }
}
