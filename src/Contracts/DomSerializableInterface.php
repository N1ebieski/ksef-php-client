<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts;

use DOMDocument;

interface DomSerializableInterface
{
    public function toDom(): DOMDocument;
}
