<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Date;

use DateTimeInterface;
use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;

final readonly class BeforeRule extends AbstractRule
{
    public function __construct(private DateTimeInterface $before)
    {
    }

    public function handle(DateTimeInterface $value, ?string $attribute = null): void
    {
        if ($value > $this->before) {
            throw new InvalidArgumentException(
                $this->getMessage(
                    sprintf('Date must be before %s.', $this->before->format('Y-m-d H:i:s')),
                    $attribute
                )
            );
        }
    }
}
