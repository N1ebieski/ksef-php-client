<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\NormalizeXml\RemoveNamespaceFromXml;

use N1ebieski\KSEFClient\Actions\AbstractAction;

final class RemoveNamespaceFromXmlAction extends AbstractAction
{
    public function __construct(
        public readonly string $xml,
    ) {
    }
}
