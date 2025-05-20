<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Support\Concerns\HasToArray;
use N1ebieski\KSEFClient\Validator\Rules\String\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class SubjectPersonName extends AbstractValueObject implements FromInterface, ArrayableInterface
{
    use HasToArray;

    public string $firstName;

    public string $surname;

    public function __construct(string $firstName, string $surname)
    {
        Validator::validate([
            'firstName' => $firstName,
            'surname' => $surname
        ], [
            'firstName' => [new MinRule(1), new MaxRule(30)],
            'surname' => [new MinRule(1), new MaxRule(381)],
        ]);

        $this->firstName = $firstName;
        $this->surname = $surname;
    }

    public static function from(string $firstName, string $surname): self
    {
        return new self($firstName, $surname);
    }
}
