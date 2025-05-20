<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Get;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;
use Override;

final class GetResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    public string $data = 'xml-string';

    #[Override]
    public function toContents(): string
    {
        return $this->data;
    }
}
