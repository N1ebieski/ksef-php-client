<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\DTOs;

use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Support\DTO;

final readonly class RequestDTO extends DTO
{
    /**
     * @param array<int, Header> $headers
     * @param string|array<string, mixed>|null $data
     */
    public function __construct(
        public Method $method = Method::Get,
        public Uri $uri = new Uri('/'),
        public array $headers = [],
        public array | string | null $data = null
    ) {
    }
}
