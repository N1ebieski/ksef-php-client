<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Certificates\Revoke;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\CertificateSerialNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\RevocationReason;

final class RevokeRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly CertificateSerialNumber $certificateSerialNumber,
        public readonly Optional | RevocationReason $revocationReason = new Optional()
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['revocationReason']);
    }
}
