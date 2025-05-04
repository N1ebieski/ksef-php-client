<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface WithInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function with(array $data): self;
}
