<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources;

use N1ebieski\KSEFClient\Contracts\Resources\Online\OnlineResourceInterface;

interface RootResourceInterface
{
    public function online(): OnlineResourceInterface;
}
