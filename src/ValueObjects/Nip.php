<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class Nip extends ValueObject implements ValueAwareInterface, Stringable
{
    public function __construct(
        public string $value,
        bool $skipValidation = false
    ) {
        if ( ! $skipValidation) {
            $this->validate();
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    private function validate(): void
    {
        if (preg_match('/^\d{10}$/', $this->value) === false) {
            throw new InvalidArgumentException('Invalid NIP number format. It should be 10 digits.');
        }

        $weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];

        /** @var array<int, int> $digits */
        $digits = array_map('intval', str_split($this->value));
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += $digits[$i] * $weights[$i];
        }

        $checksum = $sum % 11;

        if ($checksum === 1 || $digits[9] !== $checksum) {
            throw new InvalidArgumentException('Invalid NIP number checksum.');
        }
    }
}
