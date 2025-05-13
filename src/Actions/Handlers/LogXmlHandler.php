<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\Handlers;

use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;
use N1ebieski\KSEFClient\Actions\Handler;
use N1ebieski\KSEFClient\Actions\LogXmlAction;
use N1ebieski\KSEFClient\DTOs\Config;
use RuntimeException;

final readonly class LogXmlHandler extends Handler
{
    public function __construct(
        private Config $config
    ) {
    }

    public function handle(LogXmlAction $action): void
    {
        if (!$this->config->logXmlPath instanceof LogXmlPath) {
            return;
        }

        $filename = $this->config->logXmlPath->withSlashAtEnd()->value . $action->logXmlFilename->withoutSlashAtStart()->value;

        file_put_contents($filename, $action->document) ?: throw new RuntimeException(
            "Unable to write log XML file to path: {$filename}."
        );
    }
}
