<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\Invoices;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class InvoicesRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'collectiveIdentifierNumbers' => [
            '1111111111-IZ202607-65ED02180000-E7',
            '1111111111-IZ202607-62EAFD400000-B3',
        ],
        'pageSize' => 10,
        'continuationToken' => 'continuationToken',
    ];
}
