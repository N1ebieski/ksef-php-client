<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\FromArrayInterface;
use N1ebieski\KSEFClient\Contracts\WithInterface;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\HasFromArray;
use N1ebieski\KSEFClient\Support\Concerns\HasToArray;
use N1ebieski\KSEFClient\Support\Concerns\HasWith;

abstract readonly class DTO implements FromArrayInterface, ArrayableInterface, WithInterface
{
    use HasFromArray;
    use HasToArray;
    use HasWith;
}
