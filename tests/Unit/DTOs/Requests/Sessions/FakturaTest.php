<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\DTOs\Requests\Sessions;

use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;

test('toArray does not contain objects', function (): void {
    $fixture = new FakturaSprzedazyTowaruFixture();

    $faktura = Faktura::from($fixture->data);
    $array = $faktura->toArray();

    expect($array)->toBeArrayWithoutObjectsRecursively();
});
