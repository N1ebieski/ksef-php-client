<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface FromArrayInterface extends FromInterface
{
    public static function from(array $data): self;
}
