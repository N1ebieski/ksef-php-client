<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Peppol;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Peppol\PeppolResourceInterface;
use N1ebieski\KSEFClient\Requests\Peppol\Query\QueryHandler;
use N1ebieski\KSEFClient\Requests\Peppol\Query\QueryRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class PeppolResource extends AbstractResource implements PeppolResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
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
}
