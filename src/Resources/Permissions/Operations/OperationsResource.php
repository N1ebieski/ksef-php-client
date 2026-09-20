<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Permissions\Operations;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Operations\OperationsResourceInterface;
use N1ebieski\KSEFClient\Requests\Permissions\Operations\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Permissions\Operations\Status\StatusRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class OperationsResource extends AbstractResource implements OperationsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function status(StatusRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof StatusRequest === false) {
                $request = StatusRequest::from($request, $this->valinorCache);
            }

            return new StatusHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
