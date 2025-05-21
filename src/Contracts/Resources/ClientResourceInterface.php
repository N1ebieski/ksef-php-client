<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources;

use N1ebieski\KSEFClient\Contracts\Resources\Common\CommonResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\OnlineResourceInterface;

interface ClientResourceInterface
{
    public function online(): OnlineResourceInterface;

    public function common(): CommonResourceInterface;
}
