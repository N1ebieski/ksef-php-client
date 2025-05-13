<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

trait HasFromArray
{
    public static function from(array $data): static
    {
        return new \CuyZ\Valinor\MapperBuilder()
            ->allowPermissiveTypes()
            ->mapper()
            ->map(static::class, \CuyZ\Valinor\Mapper\Source\Source::array($data)->camelCaseKeys());
    }
}
