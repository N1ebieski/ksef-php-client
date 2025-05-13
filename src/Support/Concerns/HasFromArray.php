<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Mapper\Source\Source;

trait HasFromArray
{
    public static function from(array $data): static
    {
        return new MapperBuilder()
            ->allowPermissiveTypes()
            ->mapper()
            ->map(static::class, Source::array($data)->camelCaseKeys());
    }
}
