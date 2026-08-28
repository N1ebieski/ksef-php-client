<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Invoices;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Date\MaxRangeRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Invoices\DateRangeFrom;
use N1ebieski\KSEFClient\ValueObjects\Requests\Invoices\DateRangeTo;
use N1ebieski\KSEFClient\ValueObjects\Requests\Invoices\DateType;

final class DateRange extends AbstractDTO
{
    public readonly DateRangeFrom $from;

    public readonly Optional | DateRangeTo $to;

    public function __construct(
        public readonly DateType $dateType,
        DateRangeFrom $from,
        Optional | DateRangeTo $to = new Optional(),
        public readonly Optional | bool $restrictToPermanentStorageHwmDate = new Optional(),
    ) {
        Validator::validate([
            'from' => $from->value
        ], [
            'from' => [
                new MaxRangeRule(
                    $to instanceof DateRangeTo ? $to->value : new DateTimeImmutable(
                        'now',
                        $from->value->getTimezone()
                    ),
                    100
                )
            ],
        ]);

        $this->from = $from;
        $this->to = $to;
    }
}
