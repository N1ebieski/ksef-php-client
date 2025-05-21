<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Support\Str;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;

trait HasToBody
{
    use HasToArray;

    /**
     * @return array<string|int, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        $toArray = $this->toArray($keyType);

        $newArray = [];

        foreach (get_object_vars($this) as $key => $value) {
            $name = is_string($key) ? match ($keyType) {
                KeyType::Camel => Str::camel($key),
                KeyType::Snake => Str::snake($key)
            } : $key;

            if ($value instanceof BodyInterface) {
                $newArray[$name] = $value->toBody($keyType);
            }
        }

        return array_merge($toArray, $newArray);
    }
}
