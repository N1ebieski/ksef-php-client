<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Resources;

use N1ebieski\KSEFClient\Testing\Fixtures\Fixture;

abstract class RequestFixture extends Fixture
{
    /**
     * @var array<string, mixed>
     */
    abstract public array $data { get; }
}