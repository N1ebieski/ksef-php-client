<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasEquals;

enum Mode: string implements EnumInterface
{
    use HasEquals;

    case TEST = 'test';

    case DEMO = 'demo';

    case PRODUCTION = 'production';

    public function getApiUrl(): ApiUrl
    {
        return match ($this) {
            self::TEST => ApiUrl::from('https://ksef-test.mf.gov.pl/api'),
            self::DEMO => ApiUrl::from('https://ksef-demo.mf.gov.pl/api'),
            self::PRODUCTION => ApiUrl::from('https://ksef.mf.gov.pl/api'),
        };
    }
}
