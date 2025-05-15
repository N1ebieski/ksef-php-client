<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\String;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final readonly class ClassExistsRule extends AbstractRule
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
