<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Actions\CompressDocuments\CompressDocumentsAction;
use N1ebieski\KSEFClient\Actions\CompressDocuments\TarGzDocumentsHandler;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Factories\ValinorCacheFactory;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;

/** @var string|false $tempFile */
$tempFile = false;

afterEach(function () use (&$tempFile): void {
    if (is_string($tempFile) && file_exists($tempFile)) {
        unlink($tempFile);
    }
});

test('documents are ordered by numbered names after untar gz', function () use (&$tempFile): void {
    $fixtures = array_map(
        fn (int $index) => (new FakturaSprzedazyTowaruFixture())
            ->withTodayDate()
            ->withInvoiceNumber(sprintf('INV-%05d', $index))
            ->data,
        range(1, 100)
    );

    $expectedFileContents = array_map(
        fn (array $document): string => Faktura::from($document, ValinorCacheFactory::make())->toXml(),
        $fixtures
    );

    $expectedFileNames = array_map(
        fn (int $index): string => sprintf('%05d.xml', $index),
        range(1, 100)
    );

    $handler = new TarGzDocumentsHandler();
    $tarGzContent = $handler->handle(new CompressDocumentsAction($expectedFileContents));

    $tempDir = sys_get_temp_dir();
    $tempFile = tempnam($tempDir, 'targz_test_');

    if ($tempFile === false) {
        throw new RuntimeException("Unable to create temp file in {$tempDir}.");
    }

    $tempTarGzFile = "{$tempFile}.tar.gz";

    if (rename($tempFile, $tempTarGzFile) === false) {
        unlink($tempFile);

        throw new RuntimeException('Unable to prepare tar.gz file.');
    }

    $tempFile = $tempTarGzFile;

    if (file_put_contents($tempTarGzFile, $tarGzContent) === false) {
        throw new RuntimeException('Unable to write tar.gz content to temp file.');
    }

    $archive = new PharData($tempTarGzFile);
    $filesByName = [];

    foreach ($archive as $file) {
        /** @var PharFileInfo $file */
        $fileName = $file->getFilename();
        $fileContent = $file->getContent();

        expect($fileName)->toBeString();
        expect($fileContent)->toBeString();

        $filesByName[$fileName] = $fileContent;
    }

    ksort($filesByName, SORT_STRING);

    $fileNames = array_keys($filesByName);
    $fileContents = array_values($filesByName);

    expect($fileNames)->toBe($expectedFileNames);
    expect($fileContents)->toBe($expectedFileContents);
});
