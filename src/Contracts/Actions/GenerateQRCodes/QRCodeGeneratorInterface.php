<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Actions\GenerateQRCodes;

use N1ebieski\KSEFClient\ValueObjects\QRCodeGeneratorImage;

interface QRCodeGeneratorInterface
{
    /**
     * Must return the raw contents of the image, not a data URI. Some libraries encode
     * the output by default, for example chillerlan/php-qrcode with its outputBase64 option.
     */
    public function generate(string $data, ?string $label = null): QRCodeGeneratorImage;
}
