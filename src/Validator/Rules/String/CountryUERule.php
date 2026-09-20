<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\String;

use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final class CountryUERule extends AbstractRule
{
    /**
     * @var mixed[]
     */
    private const array CODES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE',
        'EL', 'HR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL',
        'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'XI'
    ];

    public function handle(string $value, ?string $attribute = null): void
    {
        if ( ! in_array($value, self::CODES, true)) {
            $this->throwRuleValidationException('Invalid country code.', $attribute);
        }
    }
}
