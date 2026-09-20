<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Requests\Security\PublicKeyCertificates;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyCertificateUsage;

interface PublicKeyCertificatesResponseInterface extends ResponseInterface
{
    /**
     * @return null|object{certificate: string, publicKeyId?: string, validFrom: string, validTo: string, usage: array<int, string>}
     */
    public function getFirstCertificateByUsage(PublicKeyCertificateUsage $type): ?object;
}
