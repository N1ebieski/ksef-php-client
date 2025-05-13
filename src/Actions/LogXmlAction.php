<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Actions\Action;
use N1ebieski\KSEFClient\Actions\ValueObjects\LogXmlFilename;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use N1ebieski\KSEFClient\ValueObjects\KSEFPublicKeyPath;
use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;
use SensitiveParameter;

final readonly class LogXmlAction extends Action
{
    public function __construct(
        public LogXmlFilename $logXmlFilename,
        public string $document
    ) {
    }
}
