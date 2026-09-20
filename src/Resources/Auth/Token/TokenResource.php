<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Auth\Token;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Auth\Token\TokenResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Auth\Token\Redeem\RedeemHandler;
use N1ebieski\KSEFClient\Requests\Auth\Token\Refresh\RefreshHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class TokenResource extends AbstractResource implements TokenResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
    ) {
    }

    public function redeem(): ResponseInterface
    {
        try {
            return new RedeemHandler($this->client)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function refresh(): ResponseInterface
    {
        try {
            return new RefreshHandler($this->client, $this->config)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
