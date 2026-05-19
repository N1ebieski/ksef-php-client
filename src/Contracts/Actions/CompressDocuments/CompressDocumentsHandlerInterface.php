<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Actions\CompressDocuments;

use N1ebieski\KSEFClient\Actions\CompressDocuments\CompressDocumentsAction;

interface CompressDocumentsHandlerInterface
{
    public function handle(CompressDocumentsAction $action): string;
}
