<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use CuyZ\Valinor\Cache\Cache;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use N1ebieski\KSEFClient\Overrides\CuyZ\Valinor\Mapper\Source\Modifier\CamelCaseKeysWithExcept;

trait HasFromArray
{
    /**
     * @param array<string, mixed> $data
     */
    public static function from(array $data, ?Cache $cache = null): static
    {
        $mapper = (new MapperBuilder())
            ->allowSuperfluousKeys()
            ->infer('string', static fn (mixed $v): string => is_array($v) && empty($v) ? '' : (string) $v)
            ->infer('?string', static fn (mixed $v): ?string => is_array($v) && empty($v) ? null : (is_string($v) ? $v : null));

        if ($cache instanceof Cache) {
            $mapper = $mapper->withCache($cache);
        }

        return $mapper
            ->allowPermissiveTypes()
            ->mapper()
            ->map(static::class, Source::iterable(
                new CamelCaseKeysWithExcept($data, keyTypeExcept: ['p_', 'uu_id'])
            ));
    }
}
