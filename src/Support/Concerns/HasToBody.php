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
     * @return array<string, mixed>
     */
    public function toBody(KeyType $keyType = KeyType::Camel): array
    {
        $toArray = $this->toArray($keyType);

        $newArray = [];

        foreach (get_object_vars($this) as $key => $value) {
            $name = match ($keyType) {
                KeyType::Camel => Str::camel($key),
                KeyType::Snake => Str::snake($key)
            };

            if ($value instanceof BodyInterface) {
                $newArray[$name] = $value->toBody($keyType);
            }
        }

        /** @var array<string, mixed> */
        return array_merge($toArray, $newArray);
    }
}
