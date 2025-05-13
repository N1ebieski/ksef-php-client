<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Overrides\CuyZ\Valinor\Mapper\Source\Modifier;

use IteratorAggregate;
use Traversable;

use function is_iterable;

/**
 * @api
 * @implements IteratorAggregate<mixed>
 */
final class CamelCaseKeysWithExcept implements IteratorAggregate
{
    /** @var array<mixed> */
    private array $source;

    /**
     * @param iterable<mixed> $source
     * @param array<int, string> $except
     */
    public function __construct(
        iterable $source,
        private array $except = []
    ) {
        $this->source = $this->replace($source);
    }

    /**
     * @param iterable<mixed> $source
     * @return array<mixed>
     */
    private function replace(iterable $source): array
    {
        $result = [];

        foreach ($source as $key => $value) {
            if (is_iterable($value)) {
                $value = $this->replace($value);
            }

            if ( ! is_string($key)) {
                $result[$key] = $value;
                continue;
            }

            if (array_filter($this->except, fn (string $except): bool => str_starts_with($key, $except)) !== []) {
                $result[$key] = $value;
                continue;
            }

            $camelCaseKey = $this->camelCaseKeys($key);

            if (isset($result[$camelCaseKey])) {
                continue;
            }

            $result[$camelCaseKey] = $value;
        }

        return $result;
    }

    private function camelCaseKeys(string $key): string
    {
        return lcfirst(str_replace([' ', '_', '-'], '', ucwords($key, ' _-')));
    }

    public function getIterator(): Traversable
    {
        yield from $this->source;
    }
}
