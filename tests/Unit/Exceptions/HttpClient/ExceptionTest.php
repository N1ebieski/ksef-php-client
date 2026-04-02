<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Exceptions\HttpClient\Exception;

test('returns header value by name in case-insensitive way', function (): void {
    $exception = new Exception(headers: [
        'Retry-After' => ['10'],
    ]);

    expect($exception->header('Retry-After'))->toBe('10');
    expect($exception->header('retry-after'))->toBe('10');
    expect($exception->header('RETRY-AFTER'))->toBe('10');
    expect($exception->header('X-Missing'))->toBeNull();
});
