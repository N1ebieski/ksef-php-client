<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;
use Throwable;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        public BaseResponseInterface $baseResponse
    ) {
        $this->throwExceptionIfError();
    }

    private function throwExceptionIfError(): void
    {
        $error = ErrorMap::tryFrom($this->baseResponse->getStatusCode());

        if ($error === null) {
            return;
        }

        /** @var object{exception: object{exceptionDetailList: array<int, object{exceptionCode: int, exceptionDescription: string}>}} $exceptionResponse */
        $exceptionResponse = $this->object();
        $exceptions = $exceptionResponse->exception->exceptionDetailList;

        $firstException = $exceptions[0] ?? null;

        /** @var class-string<Throwable> $exceptionNamespace */
        $exceptionNamespace = $error->getExceptionNamespace();

        throw new $exceptionNamespace(
            message: $firstException->exceptionDescription ?? '',
            code: $firstException->exceptionCode ?? 0,
            context: $exceptionResponse
        );
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
