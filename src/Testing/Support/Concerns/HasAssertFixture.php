<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Support\Concerns;

use DateTimeImmutable;
use N1ebieski\KSEFClient\HttpClient\Exceptions\BadRequestException;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\AbstractValueObject;

trait HasAssertFixture
{
    /**
     * @param array<string, mixed> $data
     */
    public function assertFixture(array $data, AbstractDTO $dto): void
    {
        foreach ($data as $key => $value) {
            $this->assertObjectHasProperty($key, $dto);

            if ($dto->{$key} instanceof AbstractDTO) {
                /** @var array<string, mixed> $value */
                $this->assertFixture($value, $dto->{$key});

                continue;
            }

            $this->assertEquals(match (true) {
                //@phpstan-ignore-next-line
                $dto->{$key} instanceof DateTimeImmutable => new DateTimeImmutable($value),
                default => $value,
            }, match (true) {
                //@phpstan-ignore-next-line
                $dto->{$key} instanceof AbstractValueObject => $dto->{$key}->value,
                $dto->{$key} instanceof DateTimeImmutable => $dto->{$key},
                default => $dto->{$key},
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
