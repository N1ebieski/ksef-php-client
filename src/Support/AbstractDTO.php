<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\FromArrayInterface;
use N1ebieski\KSEFClient\Contracts\WithInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasFromArray;
use N1ebieski\KSEFClient\Support\Concerns\HasToArray;
use N1ebieski\KSEFClient\Support\Concerns\HasWith;

abstract class AbstractDTO implements FromArrayInterface, ArrayableInterface, WithInterface
{
    use HasFromArray;
    use HasToArray;
    use HasWith;

    /**
     * Wraps a single XML-parsed item (scalar or associative array) into a list.
     * When SimpleXML parses a single repeated element, json_encode returns a scalar
     * or associative array instead of an indexed array.
     */
    protected static function ensureList(mixed $data): array
    {
        if (!is_array($data)) {
            return [$data];
        }
        return array_is_list($data) ? $data : [$data];
    }
}
