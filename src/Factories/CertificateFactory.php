<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use RuntimeException;
use N1ebieski\KSEFClient\Actions\ValueObjects\Certificate;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;

/**
 * Special thanks to grafinet/xades-tools > https://github.com/grafinet/xades-tools
 * I could not use their dependency directly, but most of the logic in this class
 * is their authorship
 */
final readonly class CertificateFactory
{
    public static function make(CertificatePath $certificatePath): Certificate
    {
        $pkcs12 = file_get_contents($certificatePath->path) ?: throw new RuntimeException(
            'Unable to read the cert file'
        );

        openssl_pkcs12_read($pkcs12, $data, $certificatePath->passphrase ?? '') ?: throw new RuntimeException(
            sprintf('Unable to read the cert file. OpenSSL: %s', (openssl_error_string() ?: ''))
        );

        /** @var array{pkey: string, cert: string} $data */

        $privateKey = openssl_pkey_get_private($data['pkey'], $certificatePath->passphrase) ?: throw new RuntimeException(
            sprintf('Unable to read the cert file. OpenSSL: %s', (openssl_error_string() ?: ''))
        );

        $raw = trim(str_replace(['-----BEGIN CERTIFICATE-----', '-----END CERTIFICATE-----', "\n"], '', $data['cert']));

        /** @var array{issuer: array<string, string>, serialNumberHex: string} $info */
        $info = openssl_x509_parse($data['cert']) ?: throw new RuntimeException(
            sprintf('Unable to read the cert file. OpenSSL: %s', (openssl_error_string() ?: ''))
        );

        return new Certificate($raw, $info, $privateKey);
    }
}
