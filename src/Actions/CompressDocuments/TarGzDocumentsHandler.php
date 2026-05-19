<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\CompressDocuments;

use FilesystemIterator;
use N1ebieski\KSEFClient\Actions\AbstractHandler;
use N1ebieski\KSEFClient\Contracts\Actions\CompressDocuments\CompressDocumentsHandlerInterface;
use Phar;
use PharData;
use RuntimeException;

final class TarGzDocumentsHandler extends AbstractHandler implements CompressDocumentsHandlerInterface
{
    public function handle(CompressDocumentsAction $action): string
    {
        $tempDir = sys_get_temp_dir();
        $tempFile = sprintf('%s%s%s.tar', $tempDir, DIRECTORY_SEPARATOR, uniqid('targz_'));

        $tempTarGzFile = "{$tempFile}.gz";

        try {
            $tar = new PharData($tempFile, FilesystemIterator::CURRENT_AS_FILEINFO, null, Phar::TAR);

            foreach ($action->documents as $index => $document) {
                $fileName = sprintf('%05d.xml', $index + 1);

                $tar->addFromString($fileName, $document);
            }

            $tar->compress(Phar::GZ);

            unset($tar);

            $tarGzContent = file_get_contents($tempTarGzFile);

            if ($tarGzContent === false) {
                throw new RuntimeException('Unable to read tar.gz file.');
            }

            return $tarGzContent;
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            if (file_exists($tempTarGzFile)) {
                unlink($tempTarGzFile);
            }
        }
    }
}
