<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Requests\Sessions\Batch\OpenAndSend\OpenAndSendResponseInterface;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

final class OpenAndSendResponse implements OpenAndSendResponseInterface
{
    public readonly BaseResponseInterface $baseOpenResponse;

    /**
     * @param array<int, ResponseInterface|null> $partUploadResponses
     */
    public function __construct(
        private readonly ResponseInterface $openResponse,
        public readonly array $partUploadResponses
    ) {
        $this->baseOpenResponse = $openResponse->baseResponse;
    }

    public function throwExceptionIfError(): void
    {
        $this->openResponse->throwExceptionIfError();
    }

    public function status(): int
    {
        return $this->openResponse->status();
    }

    public function header(string $name): ?string
    {
        return $this->openResponse->header($name);
    }

    public function headers(): array
    {
        return $this->openResponse->headers();
    }

    public function json(): array
    {
        return $this->openResponse->json();
    }

    public function object(): object | array
    {
        return $this->openResponse->object();
    }

    public function body(): string
    {
        return $this->openResponse->body();
    }

    public function data(): string | array
    {
        return $this->openResponse->data();
    }

    public function toArray(KeyType $keyType = KeyType::Camel, array $only = []): array
    {
        return $this->openResponse->toArray($keyType, $only);
    }
}
