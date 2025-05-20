<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

trait HasToBody
{
    /**
     * @return array<string|int, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return Arr::toBody(get_object_vars($this), $keyType);
    }
}
