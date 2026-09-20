<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Testdata\Person;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Person\PersonResourceInterface;
use N1ebieski\KSEFClient\Requests\Testdata\Person\Create\CreateHandler;
use N1ebieski\KSEFClient\Requests\Testdata\Person\Create\CreateRequest;
use N1ebieski\KSEFClient\Requests\Testdata\Person\Remove\RemoveHandler;
use N1ebieski\KSEFClient\Requests\Testdata\Person\Remove\RemoveRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class PersonResource extends AbstractResource implements PersonResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function create(CreateRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof CreateRequest === false) {
                $request = CreateRequest::from($request, $this->valinorCache);
            }

            return new CreateHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function remove(RemoveRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RemoveRequest === false) {
                $request = RemoveRequest::from($request, $this->valinorCache);
            }

            return new RemoveHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
