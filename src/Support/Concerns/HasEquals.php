<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;

trait HasEquals
{
    public function isEquals(ValueAwareInterface $value): bool
    {
        return $this->value === $value->value;
    }
}
