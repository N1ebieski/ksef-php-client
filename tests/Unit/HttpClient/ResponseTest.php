<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response as PsrResponse;
use N1ebieski\KSEFClient\HttpClient\Response;

test('returns header value by name', function (): void {
    $baseResponse = new PsrResponse(200, ['X-Test' => 'value']);
    $response = new Response($baseResponse);

    expect($response->header('X-Test'))->toBe('value');
    expect($response->header('x-test'))->toBe('value');
    expect($response->header('X-Missing'))->toBeNull();
});

test('returns all headers as array', function (): void {
    $headers = [
        'X-Test' => 'value',
        'X-Multi' => ['one', 'two'],
    ];

    $baseResponse = new PsrResponse(200, $headers);
    $response = new Response($baseResponse);

    expect($response->headers())->toBe([
        'X-Test' => ['value'],
        'X-Multi' => ['one', 'two'],
    ]);
});

test('returns json payload by key with dot notation and default value', function (): void {
    $payload = [
        'data' => [
            'key-1' => [
                'key-2' => 'Test value',
            ],
        ],
    ];

    $baseResponse = new PsrResponse(200, body: json_encode($payload, JSON_THROW_ON_ERROR));
    $response = new Response($baseResponse);

    expect($response->json())->toBe($payload)
        ->and($response->json('data.key-1.key-2'))->toBe('Test value')
        ->and($response->json('data.key-1.slug', 'fallback'))->toBe('fallback');
});
