<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Exceptions\HttpClient;

use N1ebieski\KSEFClient\Exceptions\AbstractException;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;
use Throwable;

class Exception extends AbstractException
{
    /**
     * @param array<string, array<int, string>> $headers
     * @param object|array<string, mixed>|null $context
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        private readonly array $headers = [],
        object|array|null $context = null
    ) {
        parent::__construct($message, $code, $previous, $context);
    }

    public function header(string $name): ?string
    {
        foreach ($this->headers as $headerName => $headerValues) {
            if (strcasecmp($headerName, $name) === 0) {
                return $headerValues[0] ?? null;
            }
        }

        return null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    public function toArray(KeyType $keyType = KeyType::Camel, array $only = []): array
    {
        /** @var array<string, mixed> */
        return Arr::normalize([
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'headers' => $this->headers,
            'context' => $this->context,
        ], $keyType, $only);
    }
}
