<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Limits;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Limits\LimitsResourceInterface;
use N1ebieski\KSEFClient\Requests\Limits\Context\ContextHandler;
use N1ebieski\KSEFClient\Requests\Limits\Subject\SubjectHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class LimitsResource extends AbstractResource implements LimitsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler
    ) {
    }

    public function context(): ResponseInterface
    {
        try {
            return new ContextHandler($this->client)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function subject(): ResponseInterface
    {
        try {
            return new SubjectHandler($this->client)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
