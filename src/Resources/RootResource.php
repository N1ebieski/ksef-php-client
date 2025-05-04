<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Resources\Online\OnlineResource;
use N1ebieski\KSEFClient\Resources\Resource;

final readonly class RootResource extends Resource
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
