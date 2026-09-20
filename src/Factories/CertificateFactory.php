<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use N1ebieski\KSEFClient\ValueObjects\Certificate;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use OpenSSLAsymmetricKey;
use RuntimeException;
use SensitiveParameter;

final class CertificateFactory extends AbstractFactory
{
    public static function makeFromCertificatePath(CertificatePath $certificatePath): Certificate
    {
        $pkcs12 = file_get_contents($certificatePath->path);

        if ($pkcs12 === false) {
            throw new RuntimeException('Unable to read the cert file');
        }

        return self::makeFromPkcs12($pkcs12, $certificatePath->passphrase);
    }

    public static function makeFromPkcs8(string $certificate, #[SensitiveParameter] OpenSSLAsymmetricKey | string $privateKey, #[SensitiveParameter] ?string $passphrase = null): Certificate
    {
        if ( ! $privateKey instanceof OpenSSLAsymmetricKey) {
            $privateKey = openssl_pkey_get_private($privateKey, $passphrase);
        }

        if ($privateKey === false) {
            throw new RuntimeException(
                sprintf('Unable to read the cert file. OpenSSL: %s', (openssl_error_string() ?: ''))
            );
        }

        /** @var array{issuer: array<string, string>, serialNumberHex: string, extensions: array{keyUsage: string}}|false $info */
        $info = openssl_x509_parse($certificate);

        if ($info === false) {
            throw new RuntimeException(
                sprintf('Unable to read the cert file. OpenSSL: %s', (openssl_error_string() ?: ''))
            );
        }

        return new Certificate($certificate, $info, $privateKey);
    }

    public static function makeFromPkcs12(string $certificate, #[SensitiveParameter] ?string $passphrase = null): Certificate
    {
        $pkcs12read = openssl_pkcs12_read($certificate, $data, $passphrase ?? '');

        if ($pkcs12read === false) {
            throw new RuntimeException(
                sprintf('Unable to read the cert file. OpenSSL: %s', (openssl_error_string() ?: ''))
            );
        }

        /** @var array{pkey: string, cert: string} $data */

        return self::makeFromPkcs8($data['cert'], $data['pkey'], $passphrase);
    }
}
