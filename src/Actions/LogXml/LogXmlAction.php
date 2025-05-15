<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\LogXml;

use N1ebieski\KSEFClient\Actions\AbstractAction;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;

final readonly class LogXmlAction extends AbstractAction
{
    public function __construct(
        public LogXmlFilename $logXmlFilename,
        public string $document
    ) {
    }
}
