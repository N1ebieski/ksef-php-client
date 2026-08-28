<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Create;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class CreateResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 201;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'collectiveIdentifierNumber' => '1111111111-IZ202607-65ED02180000-E7',
    ];
}
