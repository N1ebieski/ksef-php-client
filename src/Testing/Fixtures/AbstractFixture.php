<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures;

abstract class AbstractFixture
{
    /**
     * @var array<string, mixed>|string
     */
    abstract public array | string $data { get; }

    public string $name = 'default';

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
