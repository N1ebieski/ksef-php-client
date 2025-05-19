<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\EncryptDocument;

use N1ebieski\KSEFClient\Actions\AbstractHandler;
use RuntimeException;

final readonly class EncryptDocumentHandler extends AbstractHandler
{
    public function handle(EncryptDocumentAction $action): string
    {
        $encryptedDocument = openssl_encrypt(
            $action->document,
            'AES-256-CBC',
            $action->encryption->key,
            OPENSSL_RAW_DATA,
            $action->encryption->iv
        );

        if ($encryptedDocument === false) {
            throw new RuntimeException('Unable to encrypt document');
        }

        return $encryptedDocument;
    }
}
