<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\ConvertXmlToArray;

use N1ebieski\KSEFClient\Actions\AbstractAction;

final class ConvertXmlToArrayAction extends AbstractAction
{
    public function __construct(
        public readonly string $xml,
    ) {
    }
}
