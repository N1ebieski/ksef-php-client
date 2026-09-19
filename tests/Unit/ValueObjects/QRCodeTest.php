<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\ValueObjects\QRCode;

test('ensure that class builds a data uri from the given mime type', function (): void {
    $raw = 'raw-image-contents';
    $url = 'https://ksef-test.mf.gov.pl/client-app/invoice/6669669234/16-09-2026/abcDEF123';

    expect((string) QRCode::from($raw, $url))
        ->toBe('data:image/png;base64,' . base64_encode($raw));

    expect((string) QRCode::from($raw, $url, 'image/svg+xml'))
        ->toBe('data:image/svg+xml;base64,' . base64_encode($raw));
});
