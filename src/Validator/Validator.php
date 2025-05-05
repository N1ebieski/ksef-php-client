<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator;

use N1ebieski\KSEFClient\Support\Evaluation\Evaluation;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\Type;
use N1ebieski\KSEFClient\Validator\Rules\Rule;

final readonly class Validator
{
    /**
     * @param array<int, Rule>|array<string, array<int, Rule>> $rules
     */
    public static function validate(mixed $value, array $rules): void
    {
        /** @var array<string|int, mixed> $valueAsArray */
        $valueAsArray = Evaluation::evaluate($value, Type::Array);

        foreach ($valueAsArray as $attribute => $value) {
            if (is_int($attribute)) {
                $attribute = null;
            }

            /** @var array<int, Rule> $rulesSet */
            $rulesSet = $attribute !== null && isset($rules[$attribute]) ? $rules[$attribute] : $rules;

            foreach ($rulesSet as $rule) {
                $rule->handle($value, $attribute);
            }
        }
    }
}
