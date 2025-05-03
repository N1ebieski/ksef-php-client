<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface EqualsInterface
{
    public function isEquals(mixed $value): bool;
}
