<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Support\AbstractValueObject;
use SensitiveParameter;

final readonly class EncryptionKey extends AbstractValueObject
{
    public function __construct(
        #[SensitiveParameter]
        public string $key,
        #[SensitiveParameter]
        public string $iv
    ) {
    }

    public static function from(string $key, string $iv): self
    {
        return new self($key, $iv);
    }
}
