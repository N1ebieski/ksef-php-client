<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Requests\Security\PublicKeyCertificates;

use Deprecated;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyCertificateUsage;

interface PublicKeyCertificatesResponseInterface extends ResponseInterface
{
    #[Deprecated('Use getFirstCertificateByUsage instead and access the certificate property on the returned object')]
    public function getFirstByPublicKeyCertificateUsage(PublicKeyCertificateUsage $type): ?string;

    /**
     * @return null|object{certificate: string, publicKeyId?: string, validFrom: string, validTo: string, usage: array<int, string>}
     */
    public function getFirstCertificateByUsage(PublicKeyCertificateUsage $type): ?object;
}
