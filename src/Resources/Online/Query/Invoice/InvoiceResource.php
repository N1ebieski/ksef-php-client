<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Query\Invoice;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Query\Invoice\Async\AsyncResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Query\Invoice\InvoiceResourceInterface;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Sync\SyncHandler;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Sync\SyncRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Online\Query\Invoice\Async\AsyncResource;

final readonly class InvoiceResource extends AbstractResource implements InvoiceResourceInterface
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    public function sync(SyncRequest | array $request): ResponseInterface
    {
        if ($request instanceof SyncRequest == false) {
            $request = SyncRequest::from($request);
        }

        return new SyncHandler($this->client)->handle($request);
    }

    public function async(): AsyncResourceInterface
    {
        return new AsyncResource($this->client);
    }
}
