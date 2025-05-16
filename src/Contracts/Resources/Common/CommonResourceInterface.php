<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Common;

use N1ebieski\KSEFClient\Requests\Common\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Common\Status\StatusResponse;

interface CommonResourceInterface
{
    public function status(StatusRequest | array $request): StatusResponse;
}
