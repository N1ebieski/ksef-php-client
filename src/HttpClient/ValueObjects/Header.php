<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\ValueObjects;

use N1ebieski\KSEFClient\Support\ValueObject;

final readonly class Header extends ValueObject
{
    public function __construct(
        public string $name,
        public string $value
    ) {
    }
}
