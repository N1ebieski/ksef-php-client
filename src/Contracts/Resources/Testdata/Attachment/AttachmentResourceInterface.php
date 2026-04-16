<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Testdata\Attachment;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Approve\ApproveRequest;
use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Revoke\RevokeRequest;

interface AttachmentResourceInterface
{
    /**
     * @param ApproveRequest|array<string, mixed> $request
     */
    public function approve(ApproveRequest | array $request): ResponseInterface;

    /**
     * @param RevokeRequest|array<string, mixed> $request
     */
    public function revoke(RevokeRequest | array $request): ResponseInterface;
}
