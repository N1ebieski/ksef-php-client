<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Query\Invoice\Async;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Query\Invoice\Async\AsyncResourceInterface;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Fetch\FetchHandler;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Fetch\FetchRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Init\InitHandler;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Init\InitRequest;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Status\StatusRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;

final readonly class AsyncResource extends AbstractResource implements AsyncResourceInterface
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    public function init(InitRequest | array $request): ResponseInterface
    {
        if ($request instanceof InitRequest == false) {
            $request = InitRequest::from($request);
        }

        return new InitHandler($this->client)->handle($request);
    }

    public function status(StatusRequest | array $request): ResponseInterface
    {
        if ($request instanceof StatusRequest == false) {
            $request = StatusRequest::from($request);
        }

        return new StatusHandler($this->client)->handle($request);
    }

    public function fetch(FetchRequest | array $request): ResponseInterface
    {
        if ($request instanceof FetchRequest == false) {
            $request = FetchRequest::from($request);
        }

        return new FetchHandler($this->client)->handle($request);
    }
}
