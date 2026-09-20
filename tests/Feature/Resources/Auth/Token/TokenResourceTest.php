<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Feature\Resources\Auth\Token;

use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;

/** @var AbstractTestCase $this */

test('auth token refresh without changing access token', function (): void {
    /** @var AbstractTestCase $this */

    $client = $this->createClient();

    /** @var object{accessToken: object{token: string, validUntil: string}} */
    $response = $client->auth()->token()->refresh()->object();

    expect($response)->toHaveProperty('accessToken');
    expect($response->accessToken)->toHaveProperties(['token', 'validUntil']);
});
