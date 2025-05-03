<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ClientHttp\ValueObjects;

enum Method: string
{
    case GET = 'GET';

    case POST = 'POST';

    case DELETE = 'DELETE';

    case PUT = 'PUT';

    case PATCH = 'PATCH';

    case HEAD = 'HEAD';

    case OPTIONS = 'OPTIONS';

    public function hasBody(): bool
    {
        return match ($this) {
            self::GET, self::HEAD, self::OPTIONS => false,
            default => true,
        };
    }
}
