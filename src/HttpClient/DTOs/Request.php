<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\DTOs;

use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Request extends AbstractDTO
{
    /**
     * @param array<int, Header> $headers
     * @param string|array<string, mixed>|null $body
     */
    public function __construct(
        public Method $method = Method::Get,
        public Uri $uri = new Uri('/'),
        public array $headers = [],
        public array $parameters = [],
        public array | string | null $body = null
    ) {
    }

    public function getParametersAsString(): string
    {
        $parameters = Arr::filterRecursive($this->parameters, fn (mixed $value) => ! $value instanceof Optional);

        return $parameters === [] ? '' : http_build_query($parameters);
    }

    public function getBodyAsString(): string
    {
        return match (true) {
            is_string($this->body) => $this->body,
            is_array($this->body) => json_encode(
                Arr::filterRecursive($this->body, fn (mixed $value) => ! $value instanceof Optional),
                JSON_THROW_ON_ERROR
            ),
            default => ''
        };
    }
}
