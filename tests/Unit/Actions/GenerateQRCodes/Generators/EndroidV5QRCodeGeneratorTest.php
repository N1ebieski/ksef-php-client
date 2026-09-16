<?php

declare(strict_types=1);

use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Endroid\QrCode\Writer\SvgWriter;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\Generators\EndroidV5QRCodeGenerator;

test('ensure that generator renders the label only when it is given and does not leak it', function (): void {
    $qrCodeGenerator = new EndroidV5QRCodeGenerator(new QrCodeBuilder());

    $link = 'ksef-test.mf.gov.pl/client-app/invoice/6669669234/16-09-2026/abcDEF123';

    $withoutLabel = getimagesizefromstring($qrCodeGenerator->generate($link)->raw);
    $withLabel = getimagesizefromstring($qrCodeGenerator->generate($link, 'OFFLINE')->raw);
    $withoutLabelAgain = getimagesizefromstring($qrCodeGenerator->generate($link)->raw);

    if ($withoutLabel === false || $withLabel === false || $withoutLabelAgain === false) {
        throw new RuntimeException('Unable to read the image size');
    }

    // The label is rendered below the code, so it makes the image higher than wider
    expect($withoutLabel[1])->toBe($withoutLabel[0]);
    expect($withLabel[1])->toBeGreaterThan($withLabel[0]);

    // The 5.x builder is mutable, so the generator must not reuse the label from the previous call
    expect($withoutLabelAgain)->toBe($withoutLabel);
});

test('ensure that generator reports the mime type of the configured writer', function (): void {
    $link = 'ksef-test.mf.gov.pl/client-app/invoice/6669669234/16-09-2026/abcDEF123';

    $png = (new EndroidV5QRCodeGenerator(new QrCodeBuilder()))->generate($link);
    $svg = (new EndroidV5QRCodeGenerator((new QrCodeBuilder())->writer(new SvgWriter())))->generate($link);

    expect($png->mimeType)->toBe('image/png');
    expect($svg->mimeType)->toBe('image/svg+xml');

    // The contract is raw image contents, never a data URI
    expect($png->raw)->not->toStartWith('data:');
    expect($svg->raw)->not->toStartWith('data:');
});
