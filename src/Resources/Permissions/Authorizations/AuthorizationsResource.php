<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Permissions\Authorizations;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Authorizations\AuthorizationsResourceInterface;
use N1ebieski\KSEFClient\Requests\Permissions\Authorizations\Grants\GrantsHandler;
use N1ebieski\KSEFClient\Requests\Permissions\Authorizations\Grants\GrantsRequest;
use N1ebieski\KSEFClient\Requests\Permissions\Authorizations\Revoke\RevokeHandler;
use N1ebieski\KSEFClient\Requests\Permissions\Authorizations\Revoke\RevokeRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class AuthorizationsResource extends AbstractResource implements AuthorizationsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function grants(GrantsRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof GrantsRequest === false) {
                $request = GrantsRequest::from($request, $this->valinorCache);
            }

            return new GrantsHandler($this->client)->handle($request);
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
