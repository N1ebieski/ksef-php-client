<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns\FromArray\DTOs;

use N1ebieski\KSEFClient\Support\DTO;
use ReflectionParameter;

final readonly class Normalize extends DTO
{
    public function __construct(
        public ReflectionParameter $parameter,
        public mixed $value
    ) {
    }

    public function withValue(mixed $value): self
    {
        return new self($this->parameter, $value);
    }
}
