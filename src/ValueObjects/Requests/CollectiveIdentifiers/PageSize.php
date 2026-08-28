<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers;

use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\Number\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final class PageSize extends AbstractValueObject implements FromInterface, ValueAwareInterface
{
    public readonly int $value;

    public function __construct(int $value)
    {
        Validator::validate($value, [
            new MinRule(10),
            new MaxRule(200),
        ]);

        $this->value = $value;
    }

    public static function from(int $value): self
    {
        return new self($value);
    }
}
