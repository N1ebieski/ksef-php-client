<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers;

use Closure;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalize;
use ReflectionNamedType;

final readonly class FromNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        $type = $normalize->parameter->getType();

        if ($type instanceof ReflectionNamedType === false) {
            /** @var Normalize */
            return $next($normalize);
        }

        /** @var class-string $name */
        $name = $type->getName();

        if ( ! is_subclass_of($name, FromInterface::class)) {
            /** @var Normalize */
            return $next($normalize);
        }

        $normalize->value = $name::from($normalize->value);

        return $normalize;
    }
}
