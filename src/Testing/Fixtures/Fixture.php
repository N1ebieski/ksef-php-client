<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures;

use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;

abstract class Fixture
{
    /**
     * @param array<string, mixed>
     */
    abstract public array $data { get; }

    abstract public int $statusCode { get; }
}
