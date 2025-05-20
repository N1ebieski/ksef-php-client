<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

interface BodyInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Snake): array;
}
