<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Certificates;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Certificates\CertificatesResourceInterface;
use N1ebieski\KSEFClient\Requests\Certificates\Limits\LimitsHandler;
use N1ebieski\KSEFClient\Requests\Certificates\Query\QueryHandler;
use N1ebieski\KSEFClient\Requests\Certificates\Query\QueryRequest;
use N1ebieski\KSEFClient\Requests\Certificates\Retrieve\RetrieveHandler;
use N1ebieski\KSEFClient\Requests\Certificates\Retrieve\RetrieveRequest;
use N1ebieski\KSEFClient\Requests\Certificates\Revoke\RevokeHandler;
use N1ebieski\KSEFClient\Requests\Certificates\Revoke\RevokeRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Certificates\Enrollments\EnrollmentsResource;
use Throwable;

final class CertificatesResource extends AbstractResource implements CertificatesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function limits(): ResponseInterface
    {
        try {
            return new LimitsHandler($this->client)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function enrollments(): EnrollmentsResource
    {
        try {
            return new EnrollmentsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function query(QueryRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof QueryRequest === false) {
                $request = QueryRequest::from($request, $this->valinorCache);
            }

            return new QueryHandler($this->client)->handle($request);
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

    public function retrieve(RetrieveRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RetrieveRequest === false) {
                $request = RetrieveRequest::from($request, $this->valinorCache);
            }

            return new RetrieveHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
