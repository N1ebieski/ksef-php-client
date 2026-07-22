<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Testdata\Certificates\Update;

use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;
use N1ebieski\KSEFClient\ValueObjects\CertificateSerialNumber;

final class UpdateRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly CertificateSerialNumber $serialNumber,
        public readonly DateTimeInterface $validTo,
    ) {
    }
}
