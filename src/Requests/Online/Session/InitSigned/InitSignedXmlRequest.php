<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitSigned;

use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;

final readonly class InitSignedXmlRequest extends AbstractRequest implements XmlSerializableInterface
{
    public function __construct(
        public string $initSessionSigned
    ) {
    }

    public function toXml(): string
    {
        return $this->initSessionSigned;
    }
}
