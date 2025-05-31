<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs;

use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\KSEFPublicKeyPath;

final readonly class Config extends AbstractDTO
{
    public function __construct(
        public KSEFPublicKeyPath $ksefPublicKeyPath,
        public ?EncryptionKey $encryptionKey = null,
    ) {
    }
}
