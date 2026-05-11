<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Exceptions\HttpClient\RateLimitException;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;

/** @var AbstractTestCase $this */

it('throws RateLimitException with the Retry-After header when rate limit is exceeded', function (): void {
    /** @var AbstractTestCase $this */
    /** @var array<string, string> $_ENV */

    $client = $this->createClient();

    foreach (range(1, 2) as $i) {
        $request = function () use ($client): void {
            $client->invoices()->query()->metadata([
                'subjectType' => 'Subject2',
                'dateRange' => [
                    'dateType' => 'PermanentStorage',
                    'from' => new DateTimeImmutable('-5 minutes', new DateTimeZone('UTC')),
                    'to' => new DateTimeImmutable('+5 minutes', new DateTimeZone('UTC'))
                ],
            ])->object();
        };

        if ($i === 1) {
            expect($request)->not()->toThrow(RateLimitException::class);
        } else {
            expect($request)->toThrow(function (RateLimitException $exception): void {
                expect($exception->headers())->toHaveKey('Retry-After');
                expect($exception->header('Retry-After'))->toBeString();
            });
        }
    }
});
