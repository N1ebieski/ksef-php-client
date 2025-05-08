<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation\DTOs;

use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\Type;

final readonly class Normalize extends DTO
{
    /**
     * @param Type|class-string $type
     */
    public function __construct(
        public Type | string $type,
        public mixed $value
    ) {
    }

    public function withValue(mixed $value): self
    {
        return new self($this->type, $value);
    }
}
