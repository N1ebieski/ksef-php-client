<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources;

use N1ebieski\KSEFClient\Contracts\Resources\Common\CommonResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\OnlineResourceInterface;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;

interface ClientResourceInterface
{
    public function getSessionToken(): ?SessionToken;

    public function withSessionToken(SessionToken | string $sessionToken): self;

    public function online(): OnlineResourceInterface;

    public function common(): CommonResourceInterface;
}
