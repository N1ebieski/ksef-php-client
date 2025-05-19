<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Concerns;

trait HasDocumentHash
{
    public function getDocumentHash(): array
    {
        $hashSHA = base64_encode(hash('sha256', $this->toDocument(), true));
        $fileSize = strlen($this->toDocument());

        return [
            'hashSHA' => [
                'algorithm' => 'SHA-256',
                'encoding' => 'Base64',
                'value' => $hashSHA,
            ],
            'fileSize' => $fileSize,
        ];
    }

    public function getBase64Body(): string
    {
        return base64_encode($this->toDocument());
    }

    abstract public function toDocument(): string;
}
