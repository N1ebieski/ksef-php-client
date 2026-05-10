<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Faktura;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaRR\FakturaSprzedazyTowaruRolniczegoFixture;

test('toArray does not contain objects', function (): void {
    $fixture = new FakturaSprzedazyTowaruRolniczegoFixture();

    $faktura = Faktura::from($fixture->data);
    $array = $faktura->toArray();

    expect($array)->toBeArrayWithoutObjectsRecursively();
});
