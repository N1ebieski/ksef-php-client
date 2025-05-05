<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

use InvalidArgumentException;

final readonly class ClassExistsRule extends Rule
{
    public function handle(string $value, ?string $attribute = null): void
    {
        if ( ! class_exists($value)) {
            throw new InvalidArgumentException(
                $this->getMessage('Class does not exist.', $attribute)
            );
        }
    }
}
