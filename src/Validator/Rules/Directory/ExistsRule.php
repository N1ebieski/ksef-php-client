<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Directory;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\Rule;

final readonly class ExistsRule extends Rule
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
