<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Latarnia;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Latarnia\LatarniaResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Latarnia\Messages\MessagesHandler;
use N1ebieski\KSEFClient\Requests\Latarnia\Status\StatusHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class LatarniaResource extends AbstractResource implements LatarniaResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler
    ) {
    }

    public function status(): ResponseInterface
    {
        try {
            return new StatusHandler($this->client, $this->config)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function messages(): ResponseInterface
    {
        try {
            return new MessagesHandler($this->client, $this->config)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
