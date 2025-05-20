<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum KeyType implements EnumInterface
{
    case Camel;

    case Snake;
}
