<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Permissions\Query\Entities;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Query\Entities\EntitiesResourceInterface;
use N1ebieski\KSEFClient\Requests\Permissions\Query\Entities\Grants\GrantsHandler;
use N1ebieski\KSEFClient\Requests\Permissions\Query\Entities\Grants\GrantsRequest;
use N1ebieski\KSEFClient\Requests\Permissions\Query\Entities\Roles\RolesHandler;
use N1ebieski\KSEFClient\Requests\Permissions\Query\Entities\Roles\RolesRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class EntitiesResource extends AbstractResource implements EntitiesResourceInterface
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

    public function roles(RolesRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RolesRequest === false) {
                $request = RolesRequest::from($request, $this->valinorCache);
            }

            return new RolesHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
