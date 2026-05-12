<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface FromXmlArrayInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public static function fromXmlArray(array $data): self;
}
