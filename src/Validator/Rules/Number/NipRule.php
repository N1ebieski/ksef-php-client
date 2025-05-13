<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Validator\Rules\Number;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Validator\Rules\Rule;

final readonly class NipRule extends Rule
{
    public function handle(string $value, ?string $attribute = null): void
    {
        if (preg_match('/^\d{10}$/', $value) === false) {
            throw new InvalidArgumentException(
                $this->getMessage('Invalid NIP number format. It should be 10 digits.', $attribute)
            );
        }

        $weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];

        /** @var array<int, int> $digits */
        $digits = array_map('intval', str_split($value));
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += $digits[$i] * $weights[$i];
        }

        $checksum = $sum % 11;

        if ($checksum === 1 || $digits[9] !== $checksum) {
            throw new InvalidArgumentException(
                $this->getMessage('Invalid NIP number checksum.', $attribute)
            );
        }
    }
}
