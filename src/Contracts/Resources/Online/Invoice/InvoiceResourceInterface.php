<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\SendResponse;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\SendRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\InitTokenResponse;

interface InvoiceResourceInterface
{
    /**
     * @param SendRequest|array<string, mixed> $dto
     */
    public function send(SendRequest | array $dto): SendResponse;
}
