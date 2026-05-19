<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns;

use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\CompressionType;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

/**
 * @property-read FormCode $formCode
 * @property-read Optional|CompressionType $compressionType
 */
trait HasToBody
{
    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->toArray(only: ['offlineMode']);

        if ($this->compressionType instanceof CompressionType) {
            //@phpstan-ignore-next-line offsetAccess.nonOffsetAccessible
            $data['batchFile']['compressionType'] = $this->compressionType->value;
        }

        return [
            ...$data,
            'formCode' => [
                'systemCode' => $this->formCode->value,
                'schemaVersion' => $this->formCode->getSchemaVersion(),
                'value' => $this->formCode->getValue(),
            ]
        ];
    }
}
