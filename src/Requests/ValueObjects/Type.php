<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\ValueObjects;

enum Type: string
{
    case Plain = 'plain';

    case Encrypted = 'encrypted';
}
