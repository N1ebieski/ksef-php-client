<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Date;

use DateTimeImmutable;
use DateTimeInterface;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final class MaxRangeRule extends AbstractRule
{
    public function __construct(
        private readonly DateTimeInterface $end,
        private readonly int $days
    ) {
    }

    public function handle(DateTimeInterface $value, ?string $attribute = null): void
    {
        $start = DateTimeImmutable::createFromInterface($value);
        $end = DateTimeImmutable::createFromInterface($this->end);

        if ($end < $start) {
            $this->throwRuleValidationException(
                'The end date must be greater than or equal to the start date.',
                $attribute
            );
        }

        $limit = $start->modify(sprintf('+%d days', $this->days));

        if ($end >= $limit) {
            $this->throwRuleValidationException(
                'Date range must not exceed %d days.',
                $attribute,
                $this->days
            );
        }
    }
}
