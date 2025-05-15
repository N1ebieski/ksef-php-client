<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\OnlineResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\RootResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Resources\Online\OnlineResource;
use N1ebieski\KSEFClient\Resources\AbstractResource;

final readonly class RootResource extends AbstractResource implements RootResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config
    ) {
    }

    public function online(): OnlineResourceInterface
    {
        return new OnlineResource($this->client, $this->config);
    }
}
