<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Support\AbstractValueObject;

final class QRCodeGeneratorImage extends AbstractValueObject
{
    /**
     * @param string $raw Raw contents of the image, not a data URI
     */
    public function __construct(
        public readonly string $raw,
        public readonly string $mimeType = 'image/png'
    ) {
    }

    public static function from(string $raw, string $mimeType = 'image/png'): self
    {
        return new self($raw, $mimeType);
    }
}
