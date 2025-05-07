<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns\FromArray;

use ReflectionParameter;

final class Normalize
{
    public function __construct(
        public readonly ReflectionParameter $parameter,
        public mixed $value
    ) {
    }
}
