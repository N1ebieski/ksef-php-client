<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\HeadersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns\HasToBody;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns\HasToHeaders;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\CompressionType;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

final class OpenAndSendZipRequest extends AbstractRequest implements BodyInterface, HeadersInterface
{
    use HasToBody;
    use HasToHeaders;

    public function __construct(
        public readonly FormCode $formCode,
        public readonly string $faktury,
        public readonly Optional | bool $offlineMode = new Optional(),
        public readonly Optional | CompressionType $compressionType = new Optional(),
        public readonly Optional | bool $subjectIdentifierValidation = new Optional(),
    ) {
    }
}
