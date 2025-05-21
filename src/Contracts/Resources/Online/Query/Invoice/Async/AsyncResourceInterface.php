<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Query\Invoice\Async;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Fetch\FetchRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Init\InitRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Status\StatusRequest;

interface AsyncResourceInterface
{
    /**
     * @param InitRequest|array<string, mixed> $request
     */
    public function init(InitRequest | array $request): ResponseInterface;

    /**
     * @param StatusRequest|array<string, mixed> $request
     */
    public function status(StatusRequest | array $request): ResponseInterface;

    /**
     * @param FetchRequest|array<string, mixed> $request
     */
    public function fetch(FetchRequest | array $request): ResponseInterface;
}
