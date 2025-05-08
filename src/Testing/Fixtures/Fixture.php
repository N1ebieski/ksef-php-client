<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures;

abstract class Fixture
{
    abstract public int $statusCode { get; }

    /**
     * @var array<string, mixed>
     */
    abstract public array $contents { get; }
}
