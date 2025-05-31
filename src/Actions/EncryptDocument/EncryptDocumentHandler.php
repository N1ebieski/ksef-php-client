<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\EncryptDocument;

use N1ebieski\KSEFClient\Actions\AbstractHandler;
use Psr\Log\LoggerInterface;
use RuntimeException;

final readonly class EncryptDocumentHandler extends AbstractHandler
{
    public function __construct(
        private ?LoggerInterface $logger = null
    ) {
    }

    public function handle(EncryptDocumentAction $action): string
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->debug('Encrypting document', [
                'document' => $action->document
            ]);
        }

        $encryptedDocument = openssl_encrypt(
            $action->document,
            'AES-256-CBC',
            $action->encryptionKey->key,
            OPENSSL_RAW_DATA,
            $action->encryptionKey->iv
        );

        if ($encryptedDocument === false) {
            throw new RuntimeException('Unable to encrypt document');
        }

        return $encryptedDocument;
    }
}
