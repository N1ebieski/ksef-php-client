<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Permissions\Query\SubordinateEntities;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Query\SubordinateEntities\SubordinateEntitiesResourceInterface;
use N1ebieski\KSEFClient\Requests\Permissions\Query\SubordinateEntities\Roles\RolesHandler;
use N1ebieski\KSEFClient\Requests\Permissions\Query\SubordinateEntities\Roles\RolesRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class SubordinateEntitiesResource extends AbstractResource implements SubordinateEntitiesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
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
