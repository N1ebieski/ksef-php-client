<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

interface ParametersInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toParameters(KeyType $keyType = KeyType::Camel): array;
}
