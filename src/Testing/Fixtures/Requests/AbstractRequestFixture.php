<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests;

use N1ebieski\KSEFClient\Testing\Fixtures\AbstractFixture;

abstract class AbstractRequestFixture extends AbstractFixture
{
    /**
     * @var array<string, mixed>
     */
    abstract public array $data { get; }
}