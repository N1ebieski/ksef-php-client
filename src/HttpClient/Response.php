<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use JsonException;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Factories\ExceptionFactory;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Support\Str;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

final class Response implements ResponseInterface
{
    private readonly string $contents;

    private readonly int $statusCode;

    public function __construct(
        public readonly BaseResponseInterface $baseResponse,
    ) {
        $this->contents = $baseResponse->getBody()->getContents();
        $this->statusCode = $baseResponse->getStatusCode();
    }

    public function throwExceptionIfError(): void
    {
        if ($this->statusCode < 400) {
            return;
        }

        try {
            $context = $this->contents === '' ? null : $this->object();
        } catch (JsonException) {
            $context = null;
        }

        /** @var object{exception: object{exceptionDetailList: array<int, object{exceptionCode: int, exceptionDescription: string}>}}|null $context */
        $exception = ExceptionFactory::make(
            statusCode: $this->statusCode,
            headers: $this->headers(),
            context: $context
        );

        throw $exception;
    }

    public function status(): int
    {
        return $this->statusCode;
    }

    public function header(string $name): ?string
    {
        if ( ! $this->baseResponse->hasHeader($name)) {
            return null;
        }

        return $this->baseResponse->getHeaderLine($name);
    }

    public function headers(): array
    {
        /** @var array<string, array<int, string>> */
        return $this->baseResponse->getHeaders();
    }

    public function body(): string
    {
        return $this->contents;
    }

    public function object(): object | array
    {
        /** @var object|array<string, mixed> */
        return json_decode($this->contents, flags: JSON_THROW_ON_ERROR);
    }

    public function json(): array
    {
        /** @var array<string, mixed> */
        return json_decode($this->contents, true, flags: JSON_THROW_ON_ERROR);
    }

    public function data(): string | array
    {
        if (Str::isJson($this->contents)) {
            return $this->json();
        }

        if (Str::isBinary($this->contents)) {
            return '[binary data]';
        }

        return $this->contents;
    }

    public function toArray(KeyType $keyType = KeyType::Camel, array $only = []): array
    {
        /** @var array<string, mixed> */
        return Arr::normalize([
            'statusCode' => $this->statusCode,
            'contents' => $this->data(),
        ], keyType: $keyType, only: $only);
    }
}
