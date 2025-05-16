<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing;

use N1ebieski\KSEFClient\Testing\Support\Concerns\HasAssertFixture;
use N1ebieski\KSEFClient\Testing\Support\Concerns\HasClientMock;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    use HasClientMock;
    use HasAssertFixture;
}
