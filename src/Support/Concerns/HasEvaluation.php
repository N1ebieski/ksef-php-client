<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\FromInterface;

trait HasEvaluation
{
    /**
     * @param null|class-string $objectNamespace
     */
    protected function evaluate(mixed $value, ?string $objectNamespace = null): mixed
    {
        if ($objectNamespace !== null) {
            return match (true) {
                $value instanceof $objectNamespace => $value,
                is_subclass_of($objectNamespace, FromInterface::class) => $objectNamespace::from($value),
                $objectNamespace === DateTimeImmutable::class => new DateTimeImmutable($value),
                default => $value
            };
        }

        return $value;
    }
}
