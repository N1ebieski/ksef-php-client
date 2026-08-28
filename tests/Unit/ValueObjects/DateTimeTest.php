<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Exceptions\RuleValidationException;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataGodzRozpTransportu;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataGodzZakTransportu;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\DataWytworzeniaFa;

/** @var AbstractTestCase $this */

/**
 * @return array<int, array<int, string>>
 */
dataset('classnameProvider', fn (): array => [
    [DataWytworzeniaFa::class],
    [DataGodzRozpTransportu::class],
    [DataGodzZakTransportu::class],
]);

test('ensure that class has a UTC timezone for string', function (string $classname): void {
    /** @var AbstractTestCase $this */
    $datetime = new DateTime('now', new DateTimeZone('Europe/Warsaw'));
    $datetimeInUTC = (clone $datetime)->setTimezone(new DateTimeZone('UTC'));

    /** @var ValueAwareInterface $class */
    $class = new $classname($datetime->format('Y-m-d\TH:i:s'));

    /** @var DateTimeInterface $classDatetime */
    $classDatetime = $class->value;

    expect($classDatetime->getTimezone())->toEqual($datetimeInUTC->getTimezone());
})->with('classnameProvider');

test('ensure that class throws timezone exception for datetime which is not in UTC', function (string $classname): void {
    /** @var AbstractTestCase $this */
    $datetime = new DateTime('now', new DateTimeZone('Europe/Warsaw'));

    new $classname($datetime);
})->with('classnameProvider')->throws(RuleValidationException::class, 'Date must be in timezone: UTC, Z.');

test('ensure that class normalizes an explicitly offset string to UTC', function (string $classname): void {
    /** @var ValueAwareInterface $class */
    $class = new $classname('2026-08-07T16:43:45.9754372+02:00');

    /** @var DateTimeInterface $classDatetime */
    $classDatetime = $class->value;
    expect($classDatetime->getTimezone()->getName())->toBe('UTC');
    expect($classDatetime->format('Y-m-d\\TH:i:s.u\\Z'))->toBe('2026-08-07T14:43:45.975437Z');
})->with('classnameProvider');
