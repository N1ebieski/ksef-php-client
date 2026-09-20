<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use Composer\InstalledVersions;
use N1ebieski\KSEFClient\ValueObjects\CachePath;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

final class CacheFactory extends AbstractFactory
{
    private const string NAMESPACE = 'ksef-php-client';

    public static function make(?CachePath $path = null): ?CacheInterface
    {
        if (class_exists(InstalledVersions::class) === false) {
            return null;
        }

        return match (true) {
            InstalledVersions::isInstalled('symfony/cache') => self::makeSymfonyCache($path),
            default => null
        };
    }

    private static function makeSymfonyCache(?CachePath $path = null): CacheInterface
    {
        return new Psr16Cache(new FilesystemAdapter(namespace: self::NAMESPACE, directory: $path?->value));
    }
}
