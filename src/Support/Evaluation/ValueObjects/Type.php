<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;
use N1ebieski\KSEFClient\Contracts\EqualsInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasEquals;

enum Type: string implements EnumInterface, EqualsInterface
{
    use HasEquals;

    case Array = 'array';
}
