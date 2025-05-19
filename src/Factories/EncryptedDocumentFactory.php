<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use N1ebieski\KSEFClient\ValueObjects\EncryptedDocument;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use RuntimeException;
use SensitiveParameter;

final readonly class EncryptedDocumentFactory extends AbstractFactory
{
    public static function make(
        #[SensitiveParameter]
        EncryptionKey $encryption,
        #[SensitiveParameter]
        string $document,
    ): EncryptedDocument {
        $encryptedDocument = openssl_encrypt(
            $document,
            'AES-256-CBC',
            $encryption->key,
            OPENSSL_RAW_DATA,
            $encryption->iv
        );

        if ($encryptedDocument === false) {
            throw new RuntimeException('Unable to encrypt document');
        }

        return new EncryptedDocument($encryptedDocument);
    }
}
