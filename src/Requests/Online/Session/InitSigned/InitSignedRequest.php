<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitSigned;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\DTOs\InitSessionSigned;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use SensitiveParameter;

final readonly class InitSignedRequest extends AbstractRequest implements XmlSerializableInterface
{
    public function __construct(
        #[SensitiveParameter]
        public CertificatePath $certificatePath,
        public InitSessionSigned $initSessionSigned,
    ) {
    }

    public function toXml(?DOMDocument $encryptionDom = null): string
    {
        return $this->initSessionSigned->toXml($encryptionDom);
    }
}
