<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\HttpClient\Factories\ExceptionFactory;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        public BaseResponseInterface $baseResponse
    ) {
        $this->throwExceptionIfError();
    }

    private function throwExceptionIfError(): void
    {
        $statusCode = $this->baseResponse->getStatusCode();

        if ($statusCode < 400) {
            return;
        }

        throw ExceptionFactory::make($statusCode, $this->object()); //@phpstan-ignore-line
    }

    public function body(): string
    {
        return $this->baseResponse->getBody()->getContents();
    }

    public function object(): object
    {
        /** @var object */
        return json_decode($this->baseResponse->getBody()->getContents(), flags: JSON_THROW_ON_ERROR);
    }

    public function json(): array
    {
        /** @var array<string, mixed> */
        return json_decode($this->baseResponse->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);
    }
}
