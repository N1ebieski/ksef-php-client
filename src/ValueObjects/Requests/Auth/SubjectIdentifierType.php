<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\Auth;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum SubjectIdentifierType: string implements EnumInterface
{
    case CertificateSubject = 'certificateSubject';

    case CertificateFingerprint = 'certificateFingerprint';
}
