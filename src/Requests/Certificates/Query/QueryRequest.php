<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Certificates\Query;

use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\CertificateSerialNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\CertificateName;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\CertificateStatus;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\CertificateType;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\PageSize;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageOffset;

final class QueryRequest extends AbstractRequest implements BodyInterface, ParametersInterface
{
    public function __construct(
        public readonly Optional | CertificateName $name = new Optional(),
        public readonly Optional | CertificateType $type = new Optional(),
        public readonly Optional | CertificateStatus $status = new Optional(),
        public readonly Optional | CertificateSerialNumber $certificateSerialNumber = new Optional(),
        public readonly Optional | DateTimeInterface $expiresAfter = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
        public readonly Optional | PageOffset $pageOffset = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['name', 'type', 'status', 'certificateSerialNumber', 'expiresAfter']);
    }

    public function toParameters(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['pageSize', 'pageOffset']);
    }
}
