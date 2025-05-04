<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface FromResponseInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public static function fromResponse(array $data): self;
}
