<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests;

use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Overrides\CuyZ\Valinor\Mapper\Source\Modifier\CamelCaseKeysWithExcept;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;

abstract readonly class AbstractRequest extends AbstractDTO
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
