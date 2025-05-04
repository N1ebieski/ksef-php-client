<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Handlers;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Resources\Handler;

final readonly class SendHandler extends Handler
{
    public function __construct(private HttpClientInterface $client)
    {
    }
}
