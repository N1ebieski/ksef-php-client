<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs;

use N1ebieski\KSEFClient\Support\DTO;
use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;

final readonly class Config extends DTO
{
    public function __construct(
        public ?LogXmlPath $logXmlPath = null
    ) {
    }
}
