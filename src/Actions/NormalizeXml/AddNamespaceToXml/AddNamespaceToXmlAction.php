<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\NormalizeXml\AddNamespaceToXml;

use N1ebieski\KSEFClient\Actions\AbstractAction;

final class AddNamespaceToXmlAction extends AbstractAction
{
    public function __construct(
        public readonly string $xml,
        public readonly string $prefix,
        public readonly string $namespaceUri,
    ) {
    }
}
