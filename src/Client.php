<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Resources\Online\OnlineResource;

final readonly class Client
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function online(): OnlineResource
    {
        return new OnlineResource($this->client);
    }
}
