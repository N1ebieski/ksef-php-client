<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Actions\CompressDocuments\CompressDocumentsAction;
use N1ebieski\KSEFClient\Actions\CompressDocuments\ZipDocumentsHandler;
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

test('documents are ordered by numbered names after unzip', function () use (&$tempFile): void {
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

    $handler = new ZipDocumentsHandler();
    $zipContent = $handler->handle(new CompressDocumentsAction($expectedFileContents));

    $tempDir = sys_get_temp_dir();
    $tempFile = tempnam($tempDir, 'zip_test_');

    if ($tempFile === false) {
        throw new RuntimeException("Unable to create temp file in {$tempDir}.");
    }

    if (file_put_contents($tempFile, $zipContent) === false) {
        throw new RuntimeException('Unable to write zip content to temp file.');
    }

    $zip = new ZipArchive();

    expect($zip->open($tempFile))->toBeTrue();

    $filesByName = [];

    foreach (range(0, $zip->numFiles - 1) as $index) {
        $fileName = $zip->getNameIndex($index);
        $fileContent = $zip->getFromIndex($index);

        expect($fileName)->toBeString();
        expect($fileContent)->toBeString();

        $filesByName[$fileName] = $fileContent;
    }

    $zip->close();

    ksort($filesByName, SORT_STRING);

    $fileNames = array_keys($filesByName);
    $fileContents = array_values($filesByName);

    expect($fileNames)->toBe($expectedFileNames);
    expect($fileContents)->toBe($expectedFileContents);
});
