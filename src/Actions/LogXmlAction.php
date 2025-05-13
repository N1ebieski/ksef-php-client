<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions;

use N1ebieski\KSEFClient\Actions\Action;
use N1ebieski\KSEFClient\Actions\ValueObjects\LogXmlFilename;

final readonly class LogXmlAction extends Action
{
    public function __construct(
        public LogXmlFilename $logXmlFilename,
        public string $document
    ) {
    }
}
