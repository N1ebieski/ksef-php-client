<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers;

use Closure;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Attributes\AsArrayOf;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\DTOs\Normalize;

final readonly class ArrayOfNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        if ( ! is_array($normalize->value)) {
            /** @var Normalize */
            return $next($normalize);
        }

        $attributes = $normalize->parameter->getAttributes(AsArrayOf::class);

        if ($attributes === []) {
            /** @var Normalize */
            return $next($normalize);
        }

        $arrayOf = $attributes[0]->newInstance();

        if ( ! is_subclass_of($arrayOf->class, FromInterface::class)) {
            /** @var Normalize */
            return $next($normalize);
        }

        return $normalize->withValue(array_map(
            fn (mixed $item) => $arrayOf->class::from($item),
            $normalize->value
        ));
    }
}
