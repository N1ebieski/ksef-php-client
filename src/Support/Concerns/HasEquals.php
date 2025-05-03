<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

trait HasEquals
{
    public function isEquals(mixed $value): bool
    {
        return $this->value === $value->value;
    }
}
