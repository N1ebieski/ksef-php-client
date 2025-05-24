<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

trait HasToArray
{
    /**
     * @param array<int, string> $only
     * @return array<string|int, mixed>
     */
    public function toArray(KeyType $keyType = KeyType::Camel, array $only = []): array
    {
        return Arr::normalize(get_object_vars($this), $keyType, $only);
    }
}
