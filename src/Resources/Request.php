<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Mapper\Source\Source;
use N1ebieski\KSEFClient\Overrides\CuyZ\Valinor\Mapper\Source\Modifier\CamelCaseKeysWithExcept;
use N1ebieski\KSEFClient\Support\DTO;

abstract readonly class Request extends DTO
{
    public static function from(array $data): static
    {
        return new MapperBuilder()
            ->allowPermissiveTypes()
            ->mapper()
            ->map(static::class, Source::iterable(
                new CamelCaseKeysWithExcept($data, except: ['p_', 'uu_id'])
            ));
    }
}
