<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\Factories\ExceptionFactory;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

final readonly class Response implements ResponseInterface
{
    private string $contents;

    private int $statusCode;

    public function __construct(
        public BaseResponseInterface $baseResponse,
        private ExceptionHandlerInterface $exceptionHandler
    ) {
        $this->contents = $baseResponse->getBody()->getContents();
        $this->statusCode = $baseResponse->getStatusCode();

        $this->throwExceptionIfError();
    }

    private function throwExceptionIfError(): void
    {
        if ($this->statusCode < 400) {
            return;
        }

        $this->exceptionHandler->handle(
            //@phpstan-ignore-next-line
            ExceptionFactory::make($this->statusCode, $this->object())
        );
    }

    public function status(): int
    {
        return $this->statusCode;
    }

    public function body(): string
    {
        return $this->contents;
    }

    public function object(): object
    {
        /** @var object */
        return json_decode($this->contents, flags: JSON_THROW_ON_ERROR);
    }

    public function json(): array
    {
        /** @var array<string, mixed> */
        return json_decode($this->contents, true, flags: JSON_THROW_ON_ERROR);
    }

    public function toArray(KeyType $keyType = KeyType::Camel): array
    {
        return [
            'status' => $this->statusCode,
            'contents' => $this->contents,
        ];
    }
}
