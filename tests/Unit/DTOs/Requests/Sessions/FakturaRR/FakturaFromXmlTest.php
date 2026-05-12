<?php

declare(strict_types=1);

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Faktura;
use N1ebieski\KSEFClient\Factories\ValinorCacheFactory;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaRR\AbstractFakturaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaRR\FakturaSprzedazyTowaruRolniczegoFixture;

dataset('faktura fixtures', function (): array {
    $fixtures = [
        'sprzedaz towaru rolniczego' => new FakturaSprzedazyTowaruRolniczegoFixture(),
    ];

    $valinorCache = [
        'without cache' => null,
        'with cache' => ValinorCacheFactory::make(watcher: true)
    ];

    $combinations = [];

    foreach ($fixtures as $key => $fixture) {
        foreach ($valinorCache as $valinorCacheKey => $valinorCacheValue) {
            $combinations["{$key}, {$valinorCacheKey}"] = [$fixture, $valinorCacheValue];
        }
    }

    return $combinations;
});

test('fromXml round-trip produces identical XML', function (AbstractFakturaFixture $fixture, ?Cache $valinorCache): void {
    $original = Faktura::from($fixture->data, $valinorCache);

    $deserialized = Faktura::fromXml($original->toXml(), $valinorCache);

    expect($deserialized->toXml())->toBe($original->toXml());
})->with('faktura fixtures');
