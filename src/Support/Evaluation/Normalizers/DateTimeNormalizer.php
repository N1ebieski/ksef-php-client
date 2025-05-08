<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation\Normalizers;

use Closure;
use DateTimeImmutable;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Support\Evaluation\DTOs\Normalize;
use ReflectionNamedType;

final readonly class DateTimeNormalizer implements PipeInterface
{
    public function handle(Normalize $normalize, Closure $next): Normalize
    {
        if ( ! is_string($normalize->type)) {
            /** @var Normalize */
            return $next($normalize);
        }

        if ($normalize->type !== DateTimeImmutable::class) {
            /** @var Normalize */
            return $next($normalize);
        }

        return $normalize->withValue(new DateTimeImmutable($normalize->value));
    }
}
