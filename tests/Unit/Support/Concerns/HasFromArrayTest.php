<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Support\Concerns\HasFromArray;

class ExampleDTO
{
    use HasFromArray;

    public function __construct(
        public readonly string $p_13_9,
        public readonly string $example
    ) {
    }
}

test('maps DTO when array keys start with uppercase letter', function (): void {
    ExampleDTO::from([
        'P_13_9' => 'Value',
        'Example' => 'Value',
    ]);
})->throwsNoExceptions();
