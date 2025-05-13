<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions;

use N1ebieski\KSEFClient\Actions\Action;
use N1ebieski\KSEFClient\Actions\ValueObjects\Certificate;
use SensitiveParameter;

final readonly class SignDocumentAction extends Action
{
    public function __construct(
        #[SensitiveParameter]
        public Certificate $certificate,
        #[SensitiveParameter]
        public string $document,
    ) {
    }
}
