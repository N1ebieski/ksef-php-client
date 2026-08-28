<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\CollectiveIdentifiers;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\CollectiveIdentifiers\CollectiveIdentifiersResourceInterface;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Create\CreateHandler;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Create\CreateRequest;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Invoices\InvoicesHandler;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Invoices\InvoicesRequest;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\List\ListHandler;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\List\ListRequest;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Query\QueryHandler;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Query\QueryRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class CollectiveIdentifiersResource extends AbstractResource implements CollectiveIdentifiersResourceInterface
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

            return (new CreateHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function invoices(InvoicesRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof InvoicesRequest === false) {
                $request = InvoicesRequest::from($request, $this->valinorCache);
            }

            return (new InvoicesHandler($this->client))->handle($request);
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

            return (new ListHandler($this->client))->handle($request);
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

            return (new QueryHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
