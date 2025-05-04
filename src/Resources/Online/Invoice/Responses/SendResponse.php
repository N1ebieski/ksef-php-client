<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Responses;

use N1ebieski\KSEFClient\Contracts\FromResponseInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\Resources\Response;

final readonly class SendResponse extends Response
{
    public function __construct()
    {
    }

    public static function fromResponse(ResponseInterface $response): self
    {
    }
}
