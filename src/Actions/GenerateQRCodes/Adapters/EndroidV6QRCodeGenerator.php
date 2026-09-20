<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\GenerateQRCodes\Adapters;

use Endroid\QrCode\Builder\BuilderInterface as QrCodeBuilderInterface;
use N1ebieski\KSEFClient\Contracts\Actions\GenerateQRCodes\QRCodeGeneratorInterface;
use N1ebieski\KSEFClient\ValueObjects\QRCodeGeneratorImage;

/**
 * Generator for the endroid/qr-code 6.x named arguments API.
 *
 * The 5.x releases used a fluent builder instead, so they need their own
 * implementation of QRCodeGeneratorInterface.
 */
final readonly class EndroidV6QRCodeGenerator implements QRCodeGeneratorInterface
{
    public function __construct(private QrCodeBuilderInterface $qrCodeBuilder)
    {
    }

    public function generate(string $data, ?string $label = null): QRCodeGeneratorImage
    {
        $result = $this->qrCodeBuilder->build(data: $data, labelText: $label);

        return new QRCodeGeneratorImage($result->getString(), $result->getMimeType());
    }
}
