<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ClientHttp\ValueObjects;

final readonly class Header
{
    public function __construct(
        public string $name,
        public string $value
    ) {
    }
}
