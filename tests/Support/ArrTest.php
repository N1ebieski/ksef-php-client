<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Support;

use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;
use PHPUnit\Framework\TestCase;

final class ArrTest extends TestCase
{
    public function testFilterRecursive(): void
    {
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

        $this->assertSame($expectedArray, $result);
    }
}
