<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Sessions;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Sessions\SessionsResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Sessions\List\ListHandler;
use N1ebieski\KSEFClient\Requests\Sessions\List\ListRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Sessions\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Upo\UpoHandler;
use N1ebieski\KSEFClient\Requests\Sessions\Upo\UpoRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Sessions\Batch\BatchResource;
use N1ebieski\KSEFClient\Resources\Sessions\Invoices\InvoicesResource;
use N1ebieski\KSEFClient\Resources\Sessions\Online\OnlineResource;
use Psr\Log\LoggerInterface;
use Throwable;

final class SessionsResource extends AbstractResource implements SessionsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function online(): OnlineResource
    {
        try {
            return new OnlineResource($this->client, $this->config, $this->exceptionHandler, $this->logger, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function batch(): BatchResource
    {
        try {
            return new BatchResource($this->client, $this->config, $this->exceptionHandler, $this->logger, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
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

    public function list(ListRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof ListRequest === false) {
                $request = ListRequest::from($request, $this->valinorCache);
            }

            return new ListHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function invoices(): InvoicesResource
    {
        try {
            return new InvoicesResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function upo(UpoRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof UpoRequest === false) {
                $request = UpoRequest::from($request, $this->valinorCache);
            }

            return new UpoHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
