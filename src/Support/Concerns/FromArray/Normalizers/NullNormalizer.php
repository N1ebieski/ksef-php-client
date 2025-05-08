<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers;

use Closure;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\DTOs\Normalize;

final readonly class NullNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        if ($normalize->value !== null) {
            /** @var Normalize */
            return $next($normalize);
        }

        return $normalize;
    }
}
