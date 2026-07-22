<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Testdata;

use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Attachment\AttachmentResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Certificates\CertificatesResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Context\ContextResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Limits\LimitsResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Permissions\PermissionsResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Person\PersonResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\RateLimits\RateLimitsResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Subject\SubjectResourceInterface;

interface TestdataResourceInterface
{
    public function subject(): SubjectResourceInterface;

    public function person(): PersonResourceInterface;

    public function limits(): LimitsResourceInterface;

    public function rateLimits(): RateLimitsResourceInterface;

    public function attachment(): AttachmentResourceInterface;

    public function context(): ContextResourceInterface;

    public function certificates(): CertificatesResourceInterface;

    public function permissions(): PermissionsResourceInterface;
}
