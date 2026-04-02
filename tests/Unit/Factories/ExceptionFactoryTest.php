<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Exceptions\HttpClient\BadRequestException;
use N1ebieski\KSEFClient\Exceptions\HttpClient\RateLimitException;
use N1ebieski\KSEFClient\Factories\ExceptionFactory;

test('429 too many requests exception', function (): void {
    /** @var non-empty-string $json */
    $json = json_encode([
        'status' => [
            'code' => 429,
            'description' => 'Too Many Requests',
            'details' => [
                'Przekroczono limit 1 żądań na godzinę. Spróbuj ponownie po 1359 sekundach.'
            ]
        ]
    ]);

    /** @var object{status: object{code: int, description: string, details: array<int, string>}} */
    $contents = json_decode($json, flags: JSON_THROW_ON_ERROR);

    /** @var RateLimitException $exception */
    $exception = ExceptionFactory::make(statusCode: 429, headers: [], context: $contents);

    expect($exception)->toBeInstanceOf(RateLimitException::class);
    expect($exception->getMessage())->toContain($contents->status->description);
    expect($exception)->toHaveProperty('context');
    expect($exception->context)->toEqual($contents);
});

test('400 bad request exception', function (): void {
    /** @var non-empty-string $json */
    $json = json_encode([
        'exception' => [
            'exceptionDetailList' => [
                [
                    'exceptionCode' => 12345,
                    'exceptionDescription' => 'Opis błędu.',
                    'details' => [
                        'Opcjonalne dodatkowe szczegóły błędu.',
                    ],
                ],
            ],
            'referenceNumber' => 'a1b2c3d4-e5f6-4789-ab12-cd34ef567890',
            'serviceCode' => '00-c02cc3747020c605be02159bf3324f0e-eee7647dc67aa74a-00',
            'serviceCtx' => 'srvABCDA',
            'serviceName' => 'Undefined',
            'timestamp' => '2025-10-11T12:23:56.0154302',
        ],
    ]);

    /** @var object{exception: object{exceptionDetailList: array<int, object{exceptionCode: int, exceptionDescription: string}>}} */
    $contents = json_decode($json, flags: JSON_THROW_ON_ERROR);

    /** @var BadRequestException $exception */
    $exception = ExceptionFactory::make(statusCode: 400, headers: [], context: $contents);

    expect($exception)->toBeInstanceOf(BadRequestException::class);
    expect($exception->getMessage())->toContain($contents->exception->exceptionDetailList[0]->exceptionDescription);
    expect($exception)->toHaveProperty('context');
    expect($exception->context)->toEqual($contents);
});
