<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules;

use InvalidArgumentException;

final readonly class FileExtensionsRule extends Rule
{
    public function __construct(
        private array $extensions
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));

        if ( ! in_array($extension, $this->extensions)) {
            throw new InvalidArgumentException(
                $this->getMessage('File {$value} has invalid extension.', $attribute)
            );
        }
    }
}
