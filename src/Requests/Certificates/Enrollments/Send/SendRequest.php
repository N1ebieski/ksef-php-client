<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Certificates\Enrollments\Send;

use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\CertificateName;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\CertificateType;

final class SendRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly CertificateName $certificateName,
        public readonly CertificateType $certificateType,
        public readonly string $csr,
        public readonly Optional | DateTimeInterface $validFrom = new Optional()
    ) {
    }
}
