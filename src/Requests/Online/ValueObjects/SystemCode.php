<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum SystemCode: string implements EnumInterface
{
    case Fa1 = 'FA (1)';

    case Fa2 = 'FA (2)';

    case Fa3 = 'FA (3)';

    public function getSchemaVersion(): string
    {
        return match ($this) {
            self::Fa1 => '1-0E',
            self::Fa2 => '1-0E',
            self::Fa3 => '1-0E',
        };
    }

    public function getTargetNamespace(): string
    {
        return match ($this) {
            self::Fa1 => 'http://crd.gov.pl/wzor/2021/11/29/11089/',
            self::Fa2 => 'http://crd.gov.pl/wzor/2023/06/29/12648/',
            self::Fa3 => 'http://crd.gov.pl/wzor/2025/02/14/02141/'
        };
    }

    public function getWariantFormularza(): string
    {
        return match ($this) {
            self::Fa1 => '1',
            self::Fa2 => '2',
            self::Fa3 => '3',
        };
    }
}
