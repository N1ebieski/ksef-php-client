<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers;

use Closure;
use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalize;
use ReflectionNamedType;

final readonly class DateTimeNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        if ( ! is_string($normalize->value)) {
            /** @var Normalize */
            return $next($normalize);
        }

        $type = $normalize->parameter->getType();

        if ($type instanceof ReflectionNamedType === false) {
            /** @var Normalize */
            return $next($normalize);
        }

        /** @var class-string $name */
        $name = $type->getName();

        if ($name !== DateTimeImmutable::class) {
            /** @var Normalize */
            return $next($normalize);
        }

        $normalize->value = new DateTimeImmutable($normalize->value);

        return $normalize;
    }
}
