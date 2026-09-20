<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Security;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Security\SecurityResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesHandler;
use N1ebieski\KSEFClient\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesResponse;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Psr\SimpleCache\CacheInterface;
use Throwable;

final class SecurityResource extends AbstractResource implements SecurityResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?CacheInterface $cache = null
    ) {
    }

    public function publicKeyCertificates(): PublicKeyCertificatesResponse
    {
        try {
            /** @var PublicKeyCertificatesResponse */
            return new PublicKeyCertificatesHandler($this->client, $this->config, $this->cache)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
