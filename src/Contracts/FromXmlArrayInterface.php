<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

interface FromXmlArrayInterface
{
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function normalizeXmlArray(array $data): array;
}
