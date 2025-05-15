<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\ValueObjects;

enum XmlNamespace: string
{
    case Ds = 'http://www.w3.org/2000/09/xmldsig#';

    case Xades = 'http://uri.etsi.org/01903/v1.3.2#';

    case KsefOnlineAuthRequest = 'http://ksef.mf.gov.pl/schema/gtw/svc/online/auth/request/2021/10/01/0001';

    case KsefTypes = 'http://ksef.mf.gov.pl/schema/gtw/svc/types/2021/10/01/0001';

    case KsefOnlineTypes = 'http://ksef.mf.gov.pl/schema/gtw/svc/online/types/2021/10/01/0001';

    case Xsi = 'http://www.w3.org/2001/XMLSchema-instance';

    case Faktura = 'http://crd.gov.pl/wzor/2023/06/29/12648/';
}
