<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Auth\Sessions;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Auth\Sessions\SessionsResourceInterface;
use N1ebieski\KSEFClient\Requests\Auth\Sessions\List\ListHandler;
use N1ebieski\KSEFClient\Requests\Auth\Sessions\List\ListRequest;
use N1ebieski\KSEFClient\Requests\Auth\Sessions\Revoke\RevokeHandler;
use N1ebieski\KSEFClient\Requests\Auth\Sessions\Revoke\RevokeRequest;
use N1ebieski\KSEFClient\Requests\Auth\Sessions\RevokeCurrent\RevokeCurrentHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class SessionsResource extends AbstractResource implements SessionsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function list(ListRequest | array $request = []): ResponseInterface
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

    public function revokeCurrent(): ResponseInterface
    {
        try {
            return new RevokeCurrentHandler($this->client)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function revoke(RevokeRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RevokeRequest === false) {
                $request = RevokeRequest::from($request, $this->valinorCache);
            }

            return new RevokeHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
