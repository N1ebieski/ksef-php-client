<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\CollectiveIdentifiers;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Create\CreateRequest;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Invoices\InvoicesRequest;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\List\ListRequest;
use N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Query\QueryRequest;

interface CollectiveIdentifiersResourceInterface
{
    /**
     * @param CreateRequest|array<string, mixed> $request
     */
    public function create(CreateRequest | array $request): ResponseInterface;

    /**
     * @param InvoicesRequest|array<string, mixed> $request
     */
    public function invoices(InvoicesRequest | array $request): ResponseInterface;

    /**
     * @param ListRequest|array<string, mixed> $request
     */
    public function list(ListRequest | array $request): ResponseInterface;

    /**
     * @param QueryRequest|array<string, mixed> $request
     */
    public function query(QueryRequest | array $request): ResponseInterface;
}
