<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

trait HasWith
{
    public function with(array $data): self
    {
        return self::from([...$this->toArray(), ...$data]);
    }

    abstract public static function from(array $data): self;

    abstract public function toArray(): array;
}
