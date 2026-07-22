<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Testdata;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\TestdataResourceInterface;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Testdata\Attachment\AttachmentResource;
use N1ebieski\KSEFClient\Resources\Testdata\Certificates\CertificatesResource;
use N1ebieski\KSEFClient\Resources\Testdata\Context\ContextResource;
use N1ebieski\KSEFClient\Resources\Testdata\Limits\LimitsResource;
use N1ebieski\KSEFClient\Resources\Testdata\Permissions\PermissionsResource;
use N1ebieski\KSEFClient\Resources\Testdata\Person\PersonResource;
use N1ebieski\KSEFClient\Resources\Testdata\RateLimits\RateLimitsResource;
use N1ebieski\KSEFClient\Resources\Testdata\Subject\SubjectResource;
use Throwable;

final class TestdataResource extends AbstractResource implements TestdataResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function subject(): SubjectResource
    {
        try {
            return new SubjectResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function person(): PersonResource
    {
        try {
            return new PersonResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function limits(): LimitsResource
    {
        try {
            return new LimitsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function rateLimits(): RateLimitsResource
    {
        try {
            return new RateLimitsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function attachment(): AttachmentResource
    {
        try {
            return new AttachmentResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function context(): ContextResource
    {
        try {
            return new ContextResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function certificates(): CertificatesResource
    {
        try {
            return new CertificatesResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function permissions(): PermissionsResource
    {
        try {
            return new PermissionsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
