<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Evaluation\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\FromInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use N1ebieski\KSEFClient\Validator\Rules\ClassExistsRule;
use N1ebieski\KSEFClient\Validator\Validator;
use Stringable;

final readonly class ObjectNamespace extends ValueObject implements Stringable, FromInterface
{
    /**
     * @var class-string
     */
    public string $value;

    /**
     * @param class-string $value
     */
    public function __construct(string $value)
    {
        Validator::validate($value, [
            new ClassExistsRule(),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param class-string $value
     */
    public static function from(string $value): self
    {
        return new self($value);
    }
}
