<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Exceptions;

use N1ebieski\KSEFClient\Exceptions\AbstractException;

/**
 * @property-read object{status: object{code: int, description: string, details?: array<int, string>, extensions?: object}} $context
 */
final class StatusException extends AbstractException
{
}
