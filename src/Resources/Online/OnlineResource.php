<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice\InvoiceResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\OnlineResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Query\QueryResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Session\SessionResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Online\Invoice\InvoiceResource;
use N1ebieski\KSEFClient\Resources\Online\Query\QueryResource;
use N1ebieski\KSEFClient\Resources\Online\Session\SessionResource;
use Psr\Log\LoggerInterface;

final readonly class OnlineResource extends AbstractResource implements OnlineResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function session(): SessionResourceInterface
    {
        return new SessionResource($this->client, $this->config);
    }

    public function invoice(): InvoiceResourceInterface
    {
        return new InvoiceResource($this->client, $this->config, $this->logger);
    }

    public function query(): QueryResourceInterface
    {
        return new QueryResource($this->client, $this->config);
    }
}
