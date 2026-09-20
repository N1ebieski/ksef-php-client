<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Testdata\Limits\Context\Session;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Limits\Context\Session\SessionResourceInterface;
use N1ebieski\KSEFClient\Requests\Testdata\Limits\Context\Session\Limits\LimitsHandler;
use N1ebieski\KSEFClient\Requests\Testdata\Limits\Context\Session\Limits\LimitsRequest;
use N1ebieski\KSEFClient\Requests\Testdata\Limits\Context\Session\Reset\ResetHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class SessionResource extends AbstractResource implements SessionResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function limits(LimitsRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof LimitsRequest === false) {
                $request = LimitsRequest::from($request, $this->valinorCache);
            }

            return new LimitsHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function reset(): ResponseInterface
    {
        try {
            return new ResetHandler($this->client)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
