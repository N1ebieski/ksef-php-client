<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Permissions\Attachments;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Attachments\AttachmentsResourceInterface;
use N1ebieski\KSEFClient\Requests\Permissions\Attachments\Status\StatusHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class AttachmentsResource extends AbstractResource implements AttachmentsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler
    ) {
    }

    public function status(): ResponseInterface
    {
        try {
            return new StatusHandler($this->client)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
