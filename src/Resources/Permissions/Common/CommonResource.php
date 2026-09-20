<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Permissions\Common;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Common\CommonResourceInterface;
use N1ebieski\KSEFClient\Requests\Permissions\Common\Revoke\RevokeHandler;
use N1ebieski\KSEFClient\Requests\Permissions\Common\Revoke\RevokeRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class CommonResource extends AbstractResource implements CommonResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
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
