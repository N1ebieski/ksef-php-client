<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use N1ebieski\KSEFClient\Contracts\FromArrayInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasFromArray;

abstract readonly class DTO implements FromArrayInterface
{
    use HasFromArray;
}
