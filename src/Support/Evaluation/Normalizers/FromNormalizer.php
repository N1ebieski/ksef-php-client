<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation\Normalizers;

use Closure;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Evaluation\DTOs\Normalize;

final readonly class FromNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        if ( ! is_string($normalize->type)) {
            /** @var Normalize */
            return $next($normalize);
        }

        if ( ! is_subclass_of($normalize->type, FromInterface::class)) {
            /** @var Normalize */
            return $next($normalize);
        }

        return $normalize->withValue($normalize->type::from($normalize->value));
    }
}
