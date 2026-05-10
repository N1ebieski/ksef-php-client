<?php

namespace N1ebieski\KSEFClient\Tests;

use DateTimeImmutable;
use DateTimeInterface;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Exceptions\HttpClient\BadRequestException;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Testdata\RateLimits\Limits\LimitsRequestFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase as FeatureAbstractTestCase;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase as UnitAbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use Pest\Expectation;

/** @var Expectation<mixed> $this */

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

uses(UnitAbstractTestCase::class)->in('Unit');
uses(FeatureAbstractTestCase::class)
    ->beforeAll(function (): void {
        /** @var array<string, string> $_ENV */
        $client = (new ClientBuilder())
            ->withMode(Mode::Test)
            ->withIdentifier($_ENV['NIP_1'])
            ->withCertificatePath(
                Utility::basePath($_ENV['CERTIFICATE_PATH_1']),
                $_ENV['CERTIFICATE_PASSPHRASE_1']
            )
            ->build();

        $limitsFixture = new LimitsRequestFixture();

        // Limit metadata for tests/Feature/Exceptions/HttpClient/RateLimitExceptionTest.php
        $client->testdata()->rateLimits()->limits([
            'rateLimits' => [
                ...$limitsFixture->data['rateLimits'], //@phpstan-ignore-line
                'invoiceMetadata' => [
                    'perSecond' => 1,
                    'perMinute' => 1,
                    'perHour' => 100,
                ]
            ]
        ]);
    })
    ->beforeEach(function (): void {
        $client = (new ClientBuilder())
            ->withMode(Mode::Test)
            ->build();

        try {
            $client->testdata()->person()->create([
                'nip' => $_ENV['NIP_1'],
                'pesel' => $_ENV['PESEL_1'],
                'isBailiff' => false,
                'description' => 'testing',
            ]);
        } catch (BadRequestException $exception) {
            if (str_starts_with($exception->getMessage(), '30001')) {
                // ignore
            }
        }
    })
    ->afterAll(function (): void {
        $client = (new ClientBuilder())
            ->withMode(Mode::Test)
            ->build();

        foreach (['NIP_1', 'NIP_2', 'NIP_3'] as $nip) {
            $client->testdata()->subject()->remove([
                'subjectNip' => $_ENV[$nip],
            ]);
        }
    })
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

/**
* @param array<string, mixed> $data
*/
//@phpstan-ignore-next-line
expect()->extend('toBeFixture', fn (array $data) => toBeFixture($data, $this->value));

expect()->extend('toBeExceptionFixture', function (array $data): void {
    /** @var array{exception: array{exceptionCode: string, exceptionDescription: string, exceptionDetailList: array<array{exceptionCode: string, exceptionDescription: string}>}} $data */
    $firstException = $data['exception']['exceptionDetailList'][0];

    expect($this->value)->toThrow(new BadRequestException(
        message: "{$firstException['exceptionCode']} {$firstException['exceptionDescription']}",
        code: 400,
        context: (object) $data
    ));
});

expect()->extend('toBeArrayWithoutObjectsRecursively', function (): void {
    /** @var Expectation<mixed> $this */
    expect($this->value)->toBeArray();

    /** @var array<string, mixed> $value */
    $value = $this->value;

    toBeArrayWithoutObjectsRecursively($value);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * @param array<string, mixed> $values
 */
function toBeArrayWithoutObjectsRecursively(array $values, string $path = 'root'): void
{
    foreach ($values as $key => $value) {
        $currentPath = "{$path}.{$key}";

        if (is_array($value)) {
            /** @var array<string, mixed> $value */
            toBeArrayWithoutObjectsRecursively($value, $currentPath);

            continue;
        }

        expect($value)->not->toBeObject("Found object at {$currentPath}");
    }
}

/**
 * @param array<string, mixed> $data
 * @param object|array<int, object>|null $fixtureData
 */
function toBeFixture(array $data, object|array|null $fixtureData = null): void
{
    foreach ($data as $key => $value) {
        if (is_array($fixtureData)) {
            /** @var array<string, mixed> $value */
            toBeFixture($value, $fixtureData[$key]);

            continue;
        }

        expect($fixtureData)->toHaveProperty($key);

        if (is_array($value) && is_array($fixtureData->{$key}) && isset($fixtureData->{$key}[0]) && is_object($fixtureData->{$key}[0])) {
            foreach ($fixtureData->{$key} as $itemKey => $itemValue) {
                if (is_string($value[$itemKey])) {
                    $value[$itemKey] = ['value' => $value[$itemKey]];
                }

                /**
                 * @var array<string, array<string, mixed>> $value
                 * @var string $itemKey
                 * @var object $itemValue
                 */
                toBeFixture($value[$itemKey], $itemValue);
            }

            continue;
        }

        if (is_array($value) && is_object($fixtureData->{$key})) {
            /** @var array<string, mixed> $value */
            toBeFixture($value, $fixtureData->{$key});

            continue;
        }

        $expected = match (true) {
            //@phpstan-ignore-next-line
            $fixtureData->{$key} instanceof DateTimeInterface => new DateTimeImmutable($value),
            //@phpstan-ignore-next-line
            $fixtureData->{$key} instanceof ValueAwareInterface && $fixtureData->{$key}->value instanceof DateTimeInterface => new DateTimeImmutable($value),
            default => $value,
        };

        $actual = match (true) {
            $fixtureData->{$key} instanceof DateTimeInterface => $fixtureData->{$key},
            $fixtureData->{$key} instanceof ValueAwareInterface => $fixtureData->{$key}->value,
            default => $fixtureData->{$key},
        };

        expect($actual)->toEqual($expected);
    }
}
