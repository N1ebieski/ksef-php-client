<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns\FromArray;

use CuyZ\Valinor\Mapper\Source\Source;
use N1ebieski\KSEFClient\Overrides\CuyZ\Valinor\Mapper\Source\Modifier\CamelCaseKeysWithExcept;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\DTOs\Normalize;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers\ArrayOfNormalizer;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers\DateTimeNormalizer;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers\FromNormalizer;
use N1ebieski\KSEFClient\Support\Concerns\FromArray\Normalizers\NullNormalizer;
use N1ebieski\KSEFClient\Support\Pipeline;
use N1ebieski\KSEFClient\Support\Str;
use ReflectionClass;

trait HasFromArray
{
    public static function from(array $data): static
    {
        return new \CuyZ\Valinor\MapperBuilder()

            ->mapper()
            ->map(static::class, \CuyZ\Valinor\Mapper\Source\Source::iterable(
                new CamelCaseKeysWithExcept($data, except: ['p_'])
            ));
    }


    // public static function from(array $data): static
    // {
    //     $newParameters = [];

    //     $reflectionClass = new ReflectionClass(static::class);
    //     $constructor = $reflectionClass->getConstructor();

    //     if ($constructor === null) {
    //         return new static(); //@phpstan-ignore-line
    //     }

    //     $parameters = $constructor->getParameters();

    //     foreach ($parameters as $parameter) {
    //         $snakeName = Str::snake($parameter->getName());

    //         $filter = array_values(array_filter(
    //             array_unique([$snakeName, $parameter->getName()]),
    //             fn (string $value): bool => in_array($value, array_keys($data))
    //         ));

    //         if ($filter === []) {
    //             continue;
    //         }

    //         $name = $filter[0];

    //         /** @var Normalize $normalize */
    //         $normalize = new Pipeline()->through([
    //             new NullNormalizer(),
    //             new ArrayOfNormalizer(),
    //             new FromNormalizer(),
    //             new DateTimeNormalizer()
    //         ])->process(new Normalize($parameter, $data[$name]));

    //         $newParameters[$parameter->getName()] = $normalize->value;
    //     }

    //     return new static(...$newParameters); //@phpstan-ignore-line
    // }
}
