<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient;

use N1ebieski\KSEFClient\Contracts\EnumInterface;
use N1ebieski\KSEFClient\HttpClient\Exceptions\BadRequestException;
use N1ebieski\KSEFClient\HttpClient\Exceptions\InternalServerErrorException;
use N1ebieski\KSEFClient\HttpClient\Exceptions\UnknownSystemException;

enum ErrorMap: int implements EnumInterface
{
    case BadRequest = 400;

    case InternalServerError = 500;

    case UnknownSystem = 501;

    public function getExceptionNamespace(): string
    {
        return match ($this) {
            self::BadRequest => BadRequestException::class,
            self::InternalServerError => InternalServerErrorException::class,
            self::UnknownSystem => UnknownSystemException::class,
        };
    }
}
