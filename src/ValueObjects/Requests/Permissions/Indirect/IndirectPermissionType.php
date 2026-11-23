<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Indirect;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum IndirectPermissionType: string implements EnumInterface
{
    case InvoiceWrite = 'InvoiceWrite';

    case InvoiceRead = 'InvoiceRead';

    case CollectiveIdentifierManage = 'CollectiveIdentifierManage';
}
