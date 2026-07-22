<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\Testdata\Permissions;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum PermissionType: string implements EnumInterface
{
    case InvoiceRead = 'InvoiceRead';

    case InvoiceWrite = 'InvoiceWrite';

    case Introspection = 'Introspection';

    case CredentialsRead = 'CredentialsRead';

    case CredentialsManage = 'CredentialsManage';

    case EnforcementOperations = 'EnforcementOperations';

    case SubunitManage = 'SubunitManage';

    case CollectiveIdentifierManage = 'CollectiveIdentifierManage';
}
