<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Testdata\Certificates;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Testdata\Certificates\Update\UpdateRequest;

interface CertificatesResourceInterface
{
    /**
     * @param UpdateRequest|array<string, mixed> $request
     */
    public function update(UpdateRequest | array $request): ResponseInterface;
}
