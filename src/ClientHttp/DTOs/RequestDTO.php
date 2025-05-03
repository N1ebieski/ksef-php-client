<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ClientHttp\DTOs;

use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Method;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Uri;
use N1ebieski\KSEFClient\Support\DTO;

/**
 * @property-read array<int, Header> $headers
 */
final readonly class RequestDTO extends DTO
{
    public function __construct(
        public Method $method,
        public Uri $uri,
        public array $headers = [],
        public array $data = []
    ) {
    }
}
