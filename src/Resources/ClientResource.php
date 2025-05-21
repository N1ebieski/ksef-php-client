<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\ClientResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Common\CommonResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\OnlineResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Common\CommonResource;
use N1ebieski\KSEFClient\Resources\Online\OnlineResource;

final readonly class ClientResource extends AbstractResource implements ClientResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config
    ) {
    }

    public function getSessionToken(): ?SessionToken
    {
        return $this->client->getSessionToken();
    }

    public function withSessionToken(SessionToken | string $sessionToken): self
    {
        if ($sessionToken instanceof SessionToken === false) {
            $sessionToken = SessionToken::from($sessionToken);
        }

        return new self($this->client->withSessionToken($sessionToken), $this->config);
    }

    public function online(): OnlineResourceInterface
    {
        return new OnlineResource($this->client, $this->config);
    }

    public function common(): CommonResourceInterface
    {
        return new CommonResource($this->client);
    }
}
