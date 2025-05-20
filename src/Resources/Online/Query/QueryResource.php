<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Query;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\OnlineResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Query\Invoice\InvoiceResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Query\QueryResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Session\SessionResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Online\Query\Invoice\InvoiceResource;
use N1ebieski\KSEFClient\Resources\Online\Session\SessionResource;

final readonly class QueryResource extends AbstractResource implements QueryResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config
    ) {
    }

    public function invoice(): InvoiceResourceInterface
    {
        return new InvoiceResource($this->client, $this->config);
    }
}
