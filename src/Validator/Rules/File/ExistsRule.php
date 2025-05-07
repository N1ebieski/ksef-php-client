<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\File;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\Rule;

final readonly class ExistsRule extends Rule
{
    public function handle(string $value, ?string $attribute = null): void
    {
        if ( ! is_file($value)) {
            throw new InvalidArgumentException(
                $this->getMessage('File does not exist.', $attribute)
            );
        }
    }
}
