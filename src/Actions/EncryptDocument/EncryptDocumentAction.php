<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\EncryptDocument;

use N1ebieski\KSEFClient\Actions\AbstractAction;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use SensitiveParameter;

final readonly class EncryptDocumentAction extends AbstractAction
{
    public function __construct(
        #[SensitiveParameter]
        public EncryptionKey $encryption,
        #[SensitiveParameter]
        public string $document,
    ) {
    }
}
