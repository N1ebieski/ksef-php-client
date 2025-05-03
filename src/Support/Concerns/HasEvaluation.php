<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

trait HasEvaluation
{
    protected function evaluate(mixed $value, ?string $objectNamespace = null): mixed
    {
        if ($objectNamespace !== null) {
            if ($value instanceof $objectNamespace) {
                return $value;
            }

            return $objectNamespace::from($value);
        }

        return $value;
    }
}
