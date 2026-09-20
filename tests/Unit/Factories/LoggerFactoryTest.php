<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Factories;

use Monolog\Logger;
use N1ebieski\KSEFClient\Factories\LoggerFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\LogPath;

test('ensure that factory discovers monolog as a candidate implementation', function (): void {
    $logger = LoggerFactory::make(LogPath::from(Utility::basePath('var/logs/testing.log')), 'debug');

    expect($logger)->toBeInstanceOf(Logger::class);
});

test('ensure that factory returns null without a log path', function (): void {
    expect(LoggerFactory::make())->toBeNull();
});
