<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\OriginalInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Str;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

trait HasToBody
{
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        return Arr::toBody(get_object_vars($this), $keyType);
    }
}
