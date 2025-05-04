<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface ArrayableInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
