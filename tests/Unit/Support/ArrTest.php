<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;

test('filter recursive', function (): void {
    $array = [
        'a' => 'b',
        'c' => [
            'd' => new Optional(),
            'f' => [
                'g' => 'h',
                'i' => [
                    'j' => new Optional(),
                ],
            ],
        ],
    ];

    $expectedArray = [
        'a' => 'b',
        'c' => [
            'f' => [
                'g' => 'h'
            ],
        ],
    ];

    $result = Arr::filterRecursive($array, fn (mixed $value): bool => ! $value instanceof Optional);

    expect($result)->toBe($expectedArray);
});

test('array with key type except', function (): void {
    $array = [
        'camelCase' => 'camelCase',
        'p_1' => 'p_1',
        'snake_case' => [
            'uu_id' => 'uu_id',
            'p_2' => 'p_2',
        ],
    ];

    $result = Arr::normalize($array, KeyType::Camel, ['p_', 'uu_id']);

    $scan = function (array $data, array $search) use (&$scan): bool {
        /** @var array<int, string> $search */
        $found = [];

        foreach ($data as $key => $value) {
            if (is_string($key) && array_filter($search, fn (string $s): bool => str_starts_with($key, $s)) !== []) {
                $found[] = $key;
            }

            if (is_array($value)) {
                $scan($value, $search);
            }
        }

        return $found !== [];
    };

    expect($scan($result, ['p_', 'uu_id']))->toBeTrue();
    expect($scan($result, ['snake_case']))->toBeFalse();
});

test('gets value by dot notation', function (): void {
    $array = [
        'key-1' => [
            'key-2' => [
                'key-3' => 100,
                'key-4' => null,
            ],
        ],
    ];

    expect(Arr::get($array, 'key-1.key-2.key-3'))->toBe(100)
        ->and(Arr::get($array, 'key-1.key-2.key-4', 10))->toBeNull()
        ->and(Arr::get($array, 'key-1.missing-key.key-3'))->toBeNull()
        ->and(Arr::get($array, 'key-1.missing-key.key-3', 300))->toBe(300)
        ->and(Arr::get($array, 'key-1.key-2.key-3.value', 300))->toBe(300);
});
