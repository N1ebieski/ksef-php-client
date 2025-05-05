<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs;

use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\DTOs\Fa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\DTOs\Naglowek;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\DTOs\Podmiot1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\DTOs\DTOs\Podmiot2;
use N1ebieski\KSEFClient\Resources\Request;

final readonly class SendRequest extends Request
{
    public function __construct(
        public Naglowek $naglowek,
        public Podmiot1 $podmiot1,
        public Podmiot2 $podmiot2,
        public Fa $fa
    ) {
    }
}
