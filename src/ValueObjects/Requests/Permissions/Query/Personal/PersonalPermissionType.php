<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Query\Personal;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum PersonalPermissionType: string implements EnumInterface
{
    case InvoiceWrite = 'InvoiceWrite';

    case InvoiceRead = 'InvoiceRead';

    case CredentialsManage = 'CredentialsManage';

    case CredentialsRead = 'CredentialsRead';

    case Introspection = 'Introspection';

    case SubunitManage = 'SubunitManage';

    case EnforcementOperations = 'EnforcementOperations';

    case VatUeManage = 'VatUeManage';

    case CollectiveIdentifierManage = 'CollectiveIdentifierManage';
}
