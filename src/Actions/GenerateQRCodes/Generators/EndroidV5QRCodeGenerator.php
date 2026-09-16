<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\GenerateQRCodes\Generators;

use Endroid\QrCode\Builder\BuilderInterface as QrCodeBuilderInterface;
use N1ebieski\KSEFClient\Contracts\Actions\GenerateQRCodes\QRCodeGeneratorInterface;
use N1ebieski\KSEFClient\ValueObjects\QRCodeImage;

/**
 * Generator for the endroid/qr-code 5.x fluent builder API.
 *
 * The 6.x release replaced that API with named arguments on a single build() method,
 * so it needs its own implementation of QRCodeGeneratorInterface.
 */
final class EndroidV5QRCodeGenerator implements QRCodeGeneratorInterface
{
    public function __construct(private readonly QrCodeBuilderInterface $qrCodeBuilder)
    {
    }

    public function generate(string $data, ?string $label = null): QRCodeImage
    {
        // The 5.x builder is mutable, so every code has to be built from its own copy
        $qrCodeBuilder = (clone $this->qrCodeBuilder)->data($data);

        if ($label !== null) {
            $qrCodeBuilder = $qrCodeBuilder->labelText($label);
        }

        $result = $qrCodeBuilder->build();

        return new QRCodeImage($result->getString(), $result->getMimeType());
    }
}
