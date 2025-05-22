<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Async\Fetch;

use N1ebieski\KSEFClient\Actions\EncryptDocument\EncryptDocumentAction;
use N1ebieski\KSEFClient\Actions\EncryptDocument\EncryptDocumentHandler;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use Override;

final class FetchResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    public string $data = 'zip-string';

    public function withEncryptionKey(EncryptionKey $encryptionKey): self
    {
        $encryptDocument = new EncryptDocumentHandler();

        $encryptedData = $encryptDocument->handle(new EncryptDocumentAction(
            encryptionKey: $encryptionKey,
            document: $this->data,
        ));

        $this->data = $encryptedData;

        return $this;
    }

    #[Override]
    public function toContents(): string
    {
        return $this->data;
    }
}
