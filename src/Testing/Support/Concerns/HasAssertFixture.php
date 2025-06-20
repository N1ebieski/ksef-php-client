<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Support\Concerns;

use DateTimeImmutable;
use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\HttpClient\Exceptions\BadRequestException;

trait HasAssertFixture
{
    /**
     * @param array<string, mixed> $data
     */
    public function assertFixture(array $data, object $object): void
    {
        foreach ($data as $key => $value) {
            $this->assertObjectHasProperty($key, $object);

            //@phpstan-ignore-next-line
            if (is_array($value) && is_array($object->{$key}) && isset($object->{$key}[0]) && is_object($object->{$key}[0])) {
                foreach ($object->{$key} as $itemKey => $itemValue) {
                    if (is_string($value[$itemKey])) {
                        $value[$itemKey] = ['value' => $value[$itemKey]];
                    }

                    /**
                     * @var array<string, array<string, mixed>> $value
                     * @var string $itemKey
                     * @var object $itemValue
                     */
                    $this->assertFixture($value[$itemKey], $itemValue);
                }

                continue;
            }

            if (is_array($value) && is_object($object->{$key})) {
                /** @var array<string, mixed> $value */
                $this->assertFixture($value, $object->{$key});

                continue;
            }

            $this->assertEquals(match (true) {
                //@phpstan-ignore-next-line
                $object->{$key} instanceof DateTimeInterface => new DateTimeImmutable($value),
                //@phpstan-ignore-next-line
                $object->{$key} instanceof ValueAwareInterface && $object->{$key}->value instanceof DateTimeInterface => new DateTimeImmutable($value),
                default => $value,
            }, match (true) {
                //@phpstan-ignore-next-line
                $object->{$key} instanceof DateTimeInterface => $object->{$key},
                //@phpstan-ignore-next-line
                $object->{$key} instanceof ValueAwareInterface => $object->{$key}->value,
                default => $object->{$key},
            });
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function assertExceptionFixture(array $data): void
    {
        $this->expectExceptionObject(new BadRequestException(
            //@phpstan-ignore-next-line
            message: $data['exception']['exceptionDetailList'][0]['exceptionDescription'],
            //@phpstan-ignore-next-line
            code: $data['exception']['exceptionDetailList'][0]['exceptionCode'],
            context: (object) $data
        ));
    }
}
