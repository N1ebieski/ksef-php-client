<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator;

use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final readonly class Validator
{
    /**
     * @param array<int, AbstractRule>|array<string, array<int, AbstractRule>> $rules
     */
    public static function validate(mixed $values, array $rules): void
    {
        if ( ! is_array($values)) {
            $values = [$values];
        }

        foreach ($values as $attribute => $value) {
            if (is_int($attribute)) {
                $attribute = null;
            }

            /** @var array<int, AbstractRule> $rulesSet */
            $rulesSet = $attribute !== null && isset($rules[$attribute]) ? $rules[$attribute] : $rules;

            foreach ($rulesSet as $rule) {
                $rule->handle($value, $attribute);
            }
        }
    }
}
