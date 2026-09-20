<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\ValueObjects;

use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Certificate;
use RuntimeException;

/** @var AbstractTestCase $this */

/**
 * @return array<int, array<int, string>>
 */
dataset('serialNumberProvider', fn (): array => [
    ['7ecfa2d1b3c4e5f60718293a4b5c6d7e8f90a1b2', '723963274928189416273665225389311152255447114162'],
    ['00d4e1b2c3a4f50678', '15339738359156508280'],
    ['4f3a2b1c', '1329212188'],
    ['ff', '255'],
    ['00', '0'],
]);

test('ensure that class converts a hex serial number to a decimal one', function (string $serialNumberHex, string $serialNumber): void {
    /** @var AbstractTestCase $this */
    $privateKey = openssl_pkey_new([
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA
    ]);

    if ($privateKey === false) {
        throw new RuntimeException('Unable to generate a private key');
    }

    $certificate = Certificate::from(
        certificate: '',
        info: [
            'issuer' => ['CN' => 'Testowy Certyfikat'],
            'serialNumberHex' => $serialNumberHex,
            'extensions' => ['keyUsage' => 'Digital Signature']
        ],
        privateKey: $privateKey
    );

    expect($certificate->getSerialNumber())->toBe($serialNumber);
})->with('serialNumberProvider');
