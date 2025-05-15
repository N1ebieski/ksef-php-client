<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Directory;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final readonly class ExistsRule extends AbstractRule
{
    public function handle(string $value, ?string $attribute = null): void
    {
        if ( ! is_dir($value)) {
            throw new InvalidArgumentException(
                $this->getMessage('Directory does not exist.', $attribute)
            );
        }
    }
}
