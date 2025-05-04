<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum Method: string implements EnumInterface
{
    case Get = 'GET';

    case Post = 'POST';

    case Delete = 'DELETE';

    case Put = 'PUT';

    case Patch = 'PATCH';

    case Head = 'HEAD';

    case Options = 'OPTIONS';

    public function hasBody(): bool
    {
        return match ($this) {
            self::Get, self::Head, self::Options => false,
            default => true,
        };
    }
}
