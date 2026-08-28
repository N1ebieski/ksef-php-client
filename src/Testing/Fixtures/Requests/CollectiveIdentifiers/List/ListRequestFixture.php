<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\CollectiveIdentifiers\List;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class ListRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'ksefNumber' => '1111111111-20260612-6310EC800000-DA',
        'continuationToken' => 'continuationToken',
        'pageSize' => 10,
    ];
}
