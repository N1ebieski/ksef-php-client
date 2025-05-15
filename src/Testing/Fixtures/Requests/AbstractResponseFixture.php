<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests;

use N1ebieski\KSEFClient\Testing\Fixtures\AbstractFixture;

abstract class AbstractResponseFixture extends AbstractFixture
{
    abstract public int $statusCode { get; }

    /**
     * @var array<string, mixed>
     */
    abstract public array $contents { get; }
}