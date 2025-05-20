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
        'query_criteria' => [
            'type' => 'range',
            'subject_type' => 'subject2',
            'type_criteriagroup' => [
                'invoicing_date_from' => '2023-11-01T00:00:00.000+01:00',
                'invoicing_date_to' => '2023-12-01T00:00:00.000+01:00'
            ]
        ],
    ];

    public function withRangeLast2Weeks(): self
    {
        $now = new DateTimeImmutable('now');

        //@phpstan-ignore-next-line
        $this->data['query_criteria']['type'] = 'range';
        //@phpstan-ignore-next-line
        $this->data['query_criteria']['type_criteriagroup']['invoicing_date_from'] = $now->modify('-2 weeks');
        //@phpstan-ignore-next-line
        $this->data['query_criteria']['type_criteriagroup']['invoicing_date_to'] = $now;

        return $this;
    }
}
