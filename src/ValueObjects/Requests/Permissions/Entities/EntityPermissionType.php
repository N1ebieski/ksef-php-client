<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Entities;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum EntityPermissionType: string implements EnumInterface
{
    case InvoiceWrite = 'InvoiceWrite';

    case InvoiceRead = 'InvoiceRead';

    case CollectiveIdentifierManage = 'CollectiveIdentifierManage';
}
