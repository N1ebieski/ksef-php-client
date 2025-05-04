<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface FromArrayInterface extends FromInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public static function from(array $data): self;
}
