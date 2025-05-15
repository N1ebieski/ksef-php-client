<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\ValueObjects;

use N1ebieski\KSEFClient\Support\AbstractValueObject;

final readonly class Header extends AbstractValueObject
{
    public function __construct(
        public string $name,
        public string $value
    ) {
    }
}
