<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use N1ebieski\KSEFClient\Contracts\EqualsInterface;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasEquals;
use N1ebieski\KSEFClient\Support\Concerns\HasEvaluation;

abstract readonly class ValueObject implements EqualsInterface, FromInterface
{
    use HasEquals;
    use HasEvaluation;
}
