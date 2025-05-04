<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\DTOs\Naglowek;
use N1ebieski\KSEFClient\Resources\Request;

final readonly class SendRequest extends Request
{
    public function __construct(
        public Naglowek $naglowek
    ) {
    }
}
