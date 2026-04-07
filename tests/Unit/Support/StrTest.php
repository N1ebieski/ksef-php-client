<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Actions\SplitDocumentIntoParts\SplitDocumentIntoPartsAction;
use N1ebieski\KSEFClient\Actions\SplitDocumentIntoParts\SplitDocumentIntoPartsHandler;
use N1ebieski\KSEFClient\Support\Str;

test('returns false for plain text', function (): void {
    $text = 'Sample text without binary characters 1234!';

    expect(Str::isBinary($text))->toBeFalse();
});

test('returns true for utf8 string with control characters', function (): void {
    $text = "Zażółć\x01 gęślą jaźń";

    expect(Str::isBinary($text))->toBeTrue();
});

test('detects binary data in split zip parts', function (): void {
    $zipPath = tempnam(sys_get_temp_dir(), 'ksef-zip-');

    expect($zipPath)->not->toBeFalse();

    try {
        $zip = new ZipArchive();

        expect($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE))->toBeTrue();

        $zip->addFromString('document.txt', str_repeat('example payload', 128));
        $zip->close();

        $document = file_get_contents($zipPath);

        expect($document)->not->toBeFalse();

        /** @var string $document */
        $partSize = max(1, (int) floor(strlen($document) / 2));

        $parts = (new SplitDocumentIntoPartsHandler())->handle(new SplitDocumentIntoPartsAction(
            document: $document,
            partSize: $partSize
        ));

        expect(count($parts))->toBeGreaterThan(1);

        expect($parts[0])->not->toBeNull();

        expect(Str::isBinary($parts[0]))->toBeTrue();
    } finally {
        if (file_exists($zipPath)) {
            unlink($zipPath);
        }
    }
});

test('detects valid json', function (): void {
    expect(Str::isJson('{"foo":"bar"}'))->toBeTrue();
    expect(Str::isJson('[1,2,3]'))->toBeTrue();
    expect(Str::isJson('null'))->toBeTrue();
});

test('returns false for invalid json', function (): void {
    expect(Str::isJson('{"foo":"bar"'))->toBeFalse();
    expect(Str::isJson('plain text'))->toBeFalse();
    expect(Str::isJson(''))->toBeFalse();
});
