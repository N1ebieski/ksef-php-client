<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\DTOs;

use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class Request extends AbstractDTO
{
    /**
     * @var array<string, string|array<int, string>>
     */
    public array $headers;

    /**
     * @param array<string, string|array<int, string>> $headers
     * @param array<string, mixed> $parameters
     * @param string|array<string, mixed>|null $body
     */
    public function __construct(
        public Method $method = Method::Get,
        public Uri $uri = new Uri('/'),
        array $headers = [],
        public array $parameters = [],
        public array | string | null $body = null
    ) {
        $this->headers = array_merge([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ], $headers);
    }

    public function withUri(Uri $uri): self
    {
        return new self($this->method, $uri, $this->headers, $this->parameters, $this->body);
    }

    public function withHeader(string $name, string $value): self
    {
        return new self($this->method, $this->uri, array_merge($this->headers, [$name => $value]), $this->parameters, $this->body);
    }

    public function getParametersAsString(): string
    {
        $parameters = Arr::filterRecursive($this->parameters, fn (mixed $value): bool => ! $value instanceof Optional);

        return $parameters === [] ? '' : http_build_query($parameters);
    }

    public function getBodyAsString(): string
    {
        return match (true) {
            is_string($this->body) => $this->body,
            is_array($this->body) => json_encode(
                Arr::filterRecursive($this->body, fn (mixed $value): bool => ! $value instanceof Optional),
                JSON_THROW_ON_ERROR
            ),
            default => ''
        };
    }
}
