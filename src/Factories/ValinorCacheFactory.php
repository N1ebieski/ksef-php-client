<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use CuyZ\Valinor\Cache\Cache;
use CuyZ\Valinor\Cache\FileSystemCache;
use CuyZ\Valinor\Cache\FileWatchingCache;
use N1ebieski\KSEFClient\ValueObjects\CachePath;
use RuntimeException;

final class ValinorCacheFactory extends AbstractFactory
{
    /**
     * @var string
     */
    public const NAMESPACE = 'valinor-cache';

    public static function make(?CachePath $path = null, bool $watcher = false): Cache
    {
        $path ??= CachePath::from(sys_get_temp_dir() . '/' . self::NAMESPACE);

        if ( ! is_dir($path->value) && ! @mkdir($path->value, 0777, true)) {
            throw new RuntimeException("Unable to create cache directory {$path->value}.");
        }

        if ( ! is_writable($path->value)) {
            throw new RuntimeException("Cache directory {$path->value} is not writable.");
        }

        $cache = new FileSystemCache($path->value);

        if ($watcher) {
            return new FileWatchingCache($cache);
        }

        return $cache;
    }
}
