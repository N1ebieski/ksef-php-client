<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Testing\Fixtures\AbstractFixture;

abstract class AbstractResponseFixture extends AbstractFixture
{
    abstract public int $statusCode { get; }

    public function toContents(): string
    {
        return json_encode($this->data) ?: throw new InvalidArgumentException('Invalid JSON');
    }
}