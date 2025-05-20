<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

trait HasToArray
{
    /**
     * @return array<string|int, mixed>
     */
    public function toArray(KeyType $keyType = KeyType::Snake): array
    {
        return Arr::toArray(get_object_vars($this), $keyType);
    }
}
