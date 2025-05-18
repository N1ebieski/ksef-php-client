<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Utility;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final readonly class RequiredRule extends AbstractRule
{
    public function handle(mixed $value, ?string $attribute = null): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException(
                $this->getMessage('The value is required.', $attribute)
            );
        }
    }
}
