<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Common;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Common\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Common\Status\StatusResponse;

interface CommonResourceInterface
{
    /**
     * @param StatusRequest|array<string, mixed> $request
     */
    public function status(StatusRequest | array $request): ResponseInterface;
}
