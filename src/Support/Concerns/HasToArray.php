<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;

trait HasToArray
{
    public function toArray(): array
    {
        $parameters = get_object_vars($this);

        foreach ($parameters as $key => $value) {
            $parameters[$key] = match (true) {
                $value instanceof ArrayableInterface => $value->toArray(),
                $value instanceof ValueAwareInterface => $value->value,
                default => $value
            };
        }

        return $parameters;
    }
}
