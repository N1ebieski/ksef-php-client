<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Resources\Online\Session\SessionResource;

final readonly class OnlineResource
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function session(): SessionResource
    {
        return new SessionResource($this->client);
    }
}
