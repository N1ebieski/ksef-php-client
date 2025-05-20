<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\OriginalInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Str;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

trait HasToArray
{
    public function toArray(KeyType $keyType = KeyType::Snake): array
    {
        return Arr::toArray(get_object_vars($this), $keyType);
    }
}
