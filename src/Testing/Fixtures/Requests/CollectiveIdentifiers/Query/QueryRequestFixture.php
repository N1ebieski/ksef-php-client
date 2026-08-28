<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Query;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class QueryRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'collectiveIdentifierNumber' => '1111111111-IZ202607-65ED02180000-E7',
        'dateCreatedFrom' => '2026-06-01T00:00:00Z',
        'dateCreatedTo' => '2026-06-12T23:59:59Z',
        'invoiceCountFrom' => 1,
        'invoiceCountTo' => 10,
        'createdInCurrentContext' => true,
        'pageSize' => 10,
        'continuationToken' => 'continuationToken',
    ];
}
