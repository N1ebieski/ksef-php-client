<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\String;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\Rule;

final readonly class RegexRule extends Rule
{
    public function __construct(
        private string $pattern
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        if (in_array(preg_match($this->pattern, $value), [0, false])) {
            throw new InvalidArgumentException(
                $this->getMessage('Invalid regex format.', $attribute)
            );
        }
    }
}
