<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Testdata\Attachment;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Attachment\AttachmentResourceInterface;
use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Approve\ApproveHandler;
use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Approve\ApproveRequest;
use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Revoke\RevokeHandler;
use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Revoke\RevokeRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class AttachmentResource extends AbstractResource implements AttachmentResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function approve(ApproveRequest|array $request): ResponseInterface
    {
        try {
            if ($request instanceof ApproveRequest === false) {
                $request = ApproveRequest::from($request, $this->valinorCache);
            }

            return new ApproveHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function revoke(RevokeRequest|array $request): ResponseInterface
    {
        try {
            if ($request instanceof RevokeRequest === false) {
                $request = RevokeRequest::from($request, $this->valinorCache);
            }

            return new RevokeHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
