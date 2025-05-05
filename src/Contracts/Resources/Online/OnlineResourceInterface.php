<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Online;

use N1ebieski\KSEFClient\Contracts\Resources\Online\Session\SessionResourceInterface;

interface OnlineResourceInterface
{
    public function session(): SessionResourceInterface;
}
