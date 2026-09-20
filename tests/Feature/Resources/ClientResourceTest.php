<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Feature\Resources;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;

/** @var AbstractTestCase $this */

test('auto access token refresh', function (): void {
    /** @var AbstractTestCase $this */

    $client = $this->createClient();

    /** @var string $token */
    $token = $client->getAccessToken()?->token;

    $accessToken = AccessToken::from($token, new DateTimeImmutable('-15 minutes'));

    $client = $client->withAccessToken($accessToken);

    expect($accessToken->validUntil)->toBeLessThan(new DateTimeImmutable());

    $client->auth();

    /** @var AccessToken $newAccessToken */
    $newAccessToken = $client->getAccessToken();

    expect($newAccessToken->isEquals($accessToken))->toBeFalse();

    expect($newAccessToken->validUntil)->toBeGreaterThan(new DateTimeImmutable());
});
