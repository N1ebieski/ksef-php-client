<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Support\Env;

test('returns an environment variable as a string', function (): void {
    putenv('KSEF_TEST_ENV=value');

    try {
        expect(Env::string('KSEF_TEST_ENV'))->toBe('value');
    } finally {
        putenv('KSEF_TEST_ENV');
    }
});

test('throws when an environment variable is missing', function (): void {
    putenv('KSEF_MISSING_ENV');

    expect(fn (): string => Env::string('KSEF_MISSING_ENV'))
        ->toThrow(RuntimeException::class, 'Environment variable [KSEF_MISSING_ENV] is not defined.');
});
