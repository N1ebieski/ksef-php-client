<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation\Normalizers;

use Closure;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Evaluation\DTOs\Normalize;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\Type;

final readonly class ArrayNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        if (is_array($normalize->value)) {
            /** @var Normalize */
            return $next($normalize);
        }

        if ($normalize->type instanceof Type === false) {
            /** @var Normalize */
            return $next($normalize);
        }

        if ( ! $normalize->type->isEquals(Type::Array)) {
            /** @var Normalize */
            return $next($normalize);
        }

        return $normalize->withValue([$normalize->value]);
    }
}
