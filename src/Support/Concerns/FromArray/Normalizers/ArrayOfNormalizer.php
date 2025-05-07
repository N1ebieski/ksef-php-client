<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers;

use Closure;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Attributes\ArrayOf;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalize;

final readonly class ArrayOfNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        if ( ! is_array($normalize->value)) {
            /** @var Normalize */
            return $next($normalize);
        }

        $attributes = $normalize->parameter->getAttributes(ArrayOf::class);

        if ($attributes === []) {
            /** @var Normalize */
            return $next($normalize);
        }

        $normalize->value = array_map(function (mixed $item) use ($attributes): mixed {
            $arrayOf = $attributes[0]->newInstance();

            return match (true) {
                is_subclass_of($arrayOf->class, FromInterface::class) => $arrayOf->class::from($item),
                default => $item
            };
        }, $normalize->value);

        return $normalize;
    }
}
