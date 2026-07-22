<?php

declare(strict_types=1);

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Factories\ValinorCacheFactory;
use N1ebieski\KSEFClient\ValueObjects\CachePath;

test('creates a writable cache directory when it does not exist', function (): void {
    $baseDir = sprintf('%s/testing-%s', sys_get_temp_dir(), uniqid('', true));
    $cachePath = CachePath::from($baseDir . '/valinor-cache');

    $cache = ValinorCacheFactory::make($cachePath);

    expect($cache)->toBeInstanceOf(Cache::class);
    expect(is_dir($cachePath->value))->toBeTrue();
    expect(is_writable($cachePath->value))->toBeTrue();

    if (is_dir($cachePath->value)) {
        rmdir($cachePath->value);
    }

    if (is_dir($baseDir)) {
        rmdir($baseDir);
    }
});
