<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\File;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\Rule;

final readonly class ExtensionsRule extends Rule
{
    /**
     * @param array<int, string> $extensions
     */
    public function __construct(
        private array $extensions
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));

        if ( ! in_array($extension, $this->extensions)) {
            throw new InvalidArgumentException(
                $this->getMessage('File has invalid extension.', $attribute)
            );
        }
    }
}
