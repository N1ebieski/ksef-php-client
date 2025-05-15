<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\Exceptions;

use Exception;
use Throwable;

abstract class AbstractException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        public readonly ?object $context = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
