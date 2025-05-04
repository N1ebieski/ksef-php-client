<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface WithInterface
{
    public function with(array $data): self;
}
