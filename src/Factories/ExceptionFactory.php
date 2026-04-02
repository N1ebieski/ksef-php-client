<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use N1ebieski\KSEFClient\Exceptions\HttpClient\BadRequestException;
use N1ebieski\KSEFClient\Exceptions\HttpClient\ClientException;
use N1ebieski\KSEFClient\Exceptions\HttpClient\Exception;
use N1ebieski\KSEFClient\Exceptions\HttpClient\InternalServerException;
use N1ebieski\KSEFClient\Exceptions\HttpClient\RateLimitException;
use N1ebieski\KSEFClient\Exceptions\HttpClient\ServerException;
use N1ebieski\KSEFClient\Exceptions\HttpClient\UnknownSystemException;
use N1ebieski\KSEFClient\Factories\AbstractFactory;
use N1ebieski\KSEFClient\Support\Utility;

final class ExceptionFactory extends AbstractFactory
{
    /**
     * @param array<string, array<int, string>> $headers
     * @param null|object{exception?: object{exceptionDetailList: array<int, object{exceptionCode: int, exceptionDescription: string}>}, status?: object{code: int, description: string, details: array<int, string>}, message?: string, title?: string} $context
     */
    public static function make(
        int $statusCode,
        array $headers,
        ?object $context
    ): Exception {
        $message = match (true) {
            isset($context->message) => $context->message,
            isset($context->title) => $context->title,
            default => null
        };

        /** @var class-string<Exception> $exceptionNamespace */
        $exceptionNamespace = match (true) {
            $statusCode === 400 => Utility::value(function () use ($context, &$message): string {
                /** @var object{exception: object{exceptionDetailList: array<int, object{exceptionCode: int, exceptionDescription: string}>}} $context */
                $message = self::getExceptionMessage($context);

                return BadRequestException::class;
            }),
            $statusCode === 429 => Utility::value(function () use ($context, &$message): string {
                /** @var object{status: object{code: int, description: string, details: array<int, string>}} $context */
                $message = self::getStatusMessage($context);

                return RateLimitException::class;
            }),
            $statusCode === 500 => InternalServerException::class,
            $statusCode === 501 => UnknownSystemException::class,
            $statusCode > 400 && $statusCode < 500 => ClientException::class,
            $statusCode > 500 => ServerException::class,
            default => Exception::class
        };

        return new $exceptionNamespace(
            message: $message ?? '',
            code: $statusCode,
            headers: $headers,
            context: $context
        );
    }

    /**
     * @param object{status: object{code: int, description: string, details: array<int, string>}} $context
     */
    private static function getStatusMessage(object $context): string
    {
        return "{$context->status->code} {$context->status->description}";
    }

    /**
     * @param object{exception: object{exceptionDetailList: array<int, object{exceptionCode: int, exceptionDescription: string}>}} $context
     */
    private static function getExceptionMessage(object $context): ?string
    {
        $exceptions = $context->exception->exceptionDetailList;

        $firstException = $exceptions[0] ?? null;

        if ($firstException !== null) {
            return "{$firstException->exceptionCode} {$firstException->exceptionDescription}";
        }

        return null;
    }
}
