<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Factories;

use N1ebieski\KSEFClient\Factories\CacheFactory;
use Psr\SimpleCache\CacheInterface;

test('cache implementation', function (): void {
    $cache = CacheFactory::make();

    expect($cache)->toBeInstanceOf(CacheInterface::class);
});
