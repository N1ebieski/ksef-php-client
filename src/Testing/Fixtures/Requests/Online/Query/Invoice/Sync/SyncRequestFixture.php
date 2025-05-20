<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Sync;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class SyncRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'queryCriteria' => [
            'subjectType' => 'subject2',
            'typeCriteriagroup' => [
                'invoicingDateFrom' => '2023-11-01T00:00:00.000+01:00',
                'invoicingDateTo' => '2023-12-01T00:00:00.000+01:00'
            ]
        ],
    ];

    public function withRangeLast2Weeks(): self
    {
        $now = new DateTimeImmutable('now');

        //@phpstan-ignore-next-line
        $this->data['queryCriteria']['typeCriteriagroup']['invoicingDateFrom'] = $now->modify('-2 weeks');
        //@phpstan-ignore-next-line
        $this->data['queryCriteria']['typeCriteriagroup']['invoicingDateTo'] = $now;

        return $this;
    }
}
