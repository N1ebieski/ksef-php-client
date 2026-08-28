<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\List;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class ListResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'continuationToken' => 'continuationToken',
        'collectiveIdentifiers' => [
            [
                'collectiveIdentifierNumber' => '1111111111-IZ202607-65ED02180000-E7',
                'createdInCurrentContext' => true,
                'dateCreated' => '2026-07-10T12:00:00+02:00',
            ],
        ],
    ];
}
