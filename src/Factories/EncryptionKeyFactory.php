<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;

final readonly class EncryptionKeyFactory extends AbstractFactory
{
    public static function makeRandom(): EncryptionKey
    {
        return new EncryptionKey(random_bytes(32), random_bytes(16));
    }
}
