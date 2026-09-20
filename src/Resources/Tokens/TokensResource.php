<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Tokens;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Tokens\TokensResourceInterface;
use N1ebieski\KSEFClient\Requests\Tokens\Create\CreateHandler;
use N1ebieski\KSEFClient\Requests\Tokens\Create\CreateRequest;
use N1ebieski\KSEFClient\Requests\Tokens\List\ListHandler;
use N1ebieski\KSEFClient\Requests\Tokens\List\ListRequest;
use N1ebieski\KSEFClient\Requests\Tokens\Revoke\RevokeHandler;
use N1ebieski\KSEFClient\Requests\Tokens\Revoke\RevokeRequest;
use N1ebieski\KSEFClient\Requests\Tokens\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Tokens\Status\StatusRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class TokensResource extends AbstractResource implements TokensResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function create(CreateRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof CreateRequest === false) {
                $request = CreateRequest::from($request, $this->valinorCache);
            }

            return new CreateHandler($this->client)->handle($request);
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
