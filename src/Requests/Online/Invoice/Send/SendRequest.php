<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Send;

use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs\Faktura;

final readonly class SendRequest extends AbstractRequest implements XmlSerializableInterface
{
    public function __construct(
        public Faktura $faktura
    ) {
    }

    public function toXml(): string
    {
        return $this->faktura->toXml();
    }
}
