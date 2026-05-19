<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use N1ebieski\KSEFClient\Actions\CompressDocuments\TarGzDocumentsHandler;
use N1ebieski\KSEFClient\Actions\CompressDocuments\ZipDocumentsHandler;
use N1ebieski\KSEFClient\Contracts\Actions\CompressDocuments\CompressDocumentsHandlerInterface;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\CompressionType;

final class CompressDocumentsHandlerFactory extends AbstractFactory
{
    public static function make(Optional | CompressionType $compressionType): CompressDocumentsHandlerInterface
    {
        $handler = match ($compressionType) {
            CompressionType::TarGz => TarGzDocumentsHandler::class,
            default => ZipDocumentsHandler::class,
        };

        return new $handler();
    }
}
