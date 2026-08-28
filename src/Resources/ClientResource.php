<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources;

use CuyZ\Valinor\Cache\Cache;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\ClientResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Resources\CollectiveIdentifiers\CollectiveIdentifiersResource;
use N1ebieski\KSEFClient\Requests\Auth\Token\Refresh\RefreshHandler;
use N1ebieski\KSEFClient\Requests\RateLimits\RateLimitsHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Auth\AuthResource;
use N1ebieski\KSEFClient\Resources\Certificates\CertificatesResource;
use N1ebieski\KSEFClient\Resources\Invoices\InvoicesResource;
use N1ebieski\KSEFClient\Resources\Latarnia\LatarniaResource;
use N1ebieski\KSEFClient\Resources\Limits\LimitsResource;
use N1ebieski\KSEFClient\Resources\Peppol\PeppolResource;
use N1ebieski\KSEFClient\Resources\Permissions\PermissionsResource;
use N1ebieski\KSEFClient\Resources\Security\SecurityResource;
use N1ebieski\KSEFClient\Resources\Sessions\SessionsResource;
use N1ebieski\KSEFClient\Resources\Testdata\TestdataResource;
use N1ebieski\KSEFClient\Resources\Tokens\TokensResource;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\RefreshToken;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;
use Throwable;

final class ClientResource extends AbstractResource implements ClientResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?CacheInterface $cache = null,
        private readonly ?Cache $valinorCache = null,
    ) {
    }

    public function getAccessToken(): ?AccessToken
    {
        return $this->config->accessToken;
    }

    public function getRefreshToken(): ?RefreshToken
    {
        return $this->config->refreshToken;
    }

    public function withEncryptionKey(EncryptionKey $encryptionKey): self
    {
        $this->client = $this->client->withEncryptionKey($encryptionKey);
        $this->config = $this->config->withEncryptionKey($encryptionKey);

        return $this;
    }

    public function withEncryptedKey(EncryptedKey $encryptedKey): self
    {
        $this->client = $this->client->withEncryptedKey($encryptedKey);
        $this->config = $this->config->withEncryptedKey($encryptedKey);

        return $this;
    }

    public function withAccessToken(AccessToken | string $accessToken, DateTimeInterface | string | null $validUntil = null): self
    {
        if ($accessToken instanceof AccessToken === false) {
            if (is_string($validUntil)) {
                $validUntil = new DateTimeImmutable($validUntil);
            }

            $accessToken = AccessToken::from($accessToken, $validUntil);
        }

        $this->client = $this->client->withAccessToken($accessToken);
        $this->config = $this->config->withAccessToken($accessToken);

        return $this;
    }

    public function withRefreshToken(RefreshToken | string $refreshToken, DateTimeInterface | string | null $validUntil = null): self
    {
        if ($refreshToken instanceof RefreshToken === false) {
            if (is_string($validUntil)) {
                $validUntil = new DateTimeImmutable($validUntil);
            }

            $refreshToken = RefreshToken::from($refreshToken, $validUntil);
        }

        $this->config = $this->config->withRefreshToken($refreshToken);

        return $this;
    }

    private function refreshTokenIfExpired(): void
    {
        if ($this->config->accessToken?->isExpired('-1 minute') === true) {
            if ($this->config->refreshToken?->isExpired() === false) {
                /** @var object{accessToken: object{token: string, validUntil: string}} $authorisationTokenResponse */
                $authorisationTokenResponse = (new RefreshHandler($this->client, $this->config))->handle()->object();

                $this->withAccessToken(AccessToken::from(
                    token: $authorisationTokenResponse->accessToken->token,
                    validUntil: new DateTimeImmutable($authorisationTokenResponse->accessToken->validUntil)
                ));

                return;
            }

            throw new RuntimeException('Access token and refresh token are expired.');
        }
    }

    public function auth(): AuthResource
    {
        try {
            $this->refreshTokenIfExpired();

            return new AuthResource($this->client, $this->config, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function limits(): LimitsResource
    {
        try {
            $this->refreshTokenIfExpired();

            return new LimitsResource($this->client, $this->exceptionHandler);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function rateLimits(): ResponseInterface
    {
        try {
            return (new RateLimitsHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function security(): SecurityResource
    {
        try {
            return new SecurityResource($this->client, $this->config, $this->exceptionHandler, $this->cache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function sessions(): SessionsResource
    {
        try {
            $this->refreshTokenIfExpired();

            return new SessionsResource($this->client, $this->config, $this->exceptionHandler, $this->logger, $this->valinorCache);
        } catch (Exception $exception) {
            throw $this->exceptionHandler->handle($exception);
        }
    }

    public function invoices(): InvoicesResource
    {
        try {
            $this->refreshTokenIfExpired();

            return new InvoicesResource($this->client, $this->config, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function collectiveIdentifiers(): CollectiveIdentifiersResource
    {
        try {
            $this->refreshTokenIfExpired();

            return new CollectiveIdentifiersResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function permissions(): PermissionsResource
    {
        try {
            $this->refreshTokenIfExpired();

            return new PermissionsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function certificates(): CertificatesResource
    {
        try {
            $this->refreshTokenIfExpired();

            return new CertificatesResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function tokens(): TokensResource
    {
        try {
            $this->refreshTokenIfExpired();

            return new TokensResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function peppol(): PeppolResource
    {
        try {
            return new PeppolResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function testdata(): TestdataResource
    {
        try {
            return new TestdataResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function latarnia(): LatarniaResource
    {
        try {
            return new LatarniaResource($this->client, $this->config, $this->exceptionHandler);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
