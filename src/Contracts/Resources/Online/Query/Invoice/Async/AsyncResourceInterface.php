<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Query\Invoice\Async;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Init\InitRequest;

interface AsyncResourceInterface
{
    /**
     * @param InitRequest|array<string, mixed> $request
     */
    public function init(InitRequest | array $request): ResponseInterface;
}
