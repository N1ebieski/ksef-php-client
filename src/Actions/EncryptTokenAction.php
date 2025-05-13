<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Actions\Action;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use N1ebieski\KSEFClient\ValueObjects\KSEFPublicKeyPath;
use SensitiveParameter;

final readonly class EncryptTokenAction extends Action
{
    public function __construct(
        #[SensitiveParameter]
        public ApiToken $apiToken,
        #[SensitiveParameter]
        public DateTimeImmutable $timestamp,
        public KSEFPublicKeyPath $publicKeyPath,
    ) {
    }
}
