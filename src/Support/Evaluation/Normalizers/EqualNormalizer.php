<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation\Normalizers;

use Closure;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Evaluation\DTOs\Normalize;

final readonly class EqualNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        if ( ! is_string($normalize->type)) {
            /** @var Normalize */
            return $next($normalize);
        }

        if ($normalize->value instanceof $normalize->type === false) {
            /** @var Normalize */
            return $next($normalize);
        }

        return $normalize;
    }
}
