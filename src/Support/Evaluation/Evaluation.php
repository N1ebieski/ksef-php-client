<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation;

use N1ebieski\KSEFClient\Support\Evaluation\DTOs\Normalize;
use N1ebieski\KSEFClient\Support\Evaluation\Normalizers\ArrayNormalizer;
use N1ebieski\KSEFClient\Support\Evaluation\Normalizers\DateTimeNormalizer;
use N1ebieski\KSEFClient\Support\Evaluation\Normalizers\EqualNormalizer;
use N1ebieski\KSEFClient\Support\Evaluation\Normalizers\FromNormalizer;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\Type;
use N1ebieski\KSEFClient\Support\Pipeline;

final readonly class Evaluation
{
    /**
     * @param Type|class-string $type
     */
    public static function evaluate(mixed $value, Type | string $type): mixed
    {
        /** @var Normalize $normalize */
        $normalize = new Pipeline()->through([
            new EqualNormalizer(),
            new ArrayNormalizer(),
            new FromNormalizer(),
            new DateTimeNormalizer()
        ])->process(new Normalize($type, $value));

        return $normalize->value;
    }
}
