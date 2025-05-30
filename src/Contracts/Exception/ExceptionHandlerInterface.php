<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Exception;

use N1ebieski\KSEFClient\Exception\AbstractException;

interface ExceptionHandlerInterface
{
    public function handle(AbstractException $exception): void;
}
