<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

use InvalidArgumentException;

final readonly class FileExistsRule extends Rule
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
