<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Get;

use Override;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class GetResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'contents' => 'xml-string'
    ];

    #[Override]
    public function toContents(): string
    {
        /** @var string */
        return $this->data['contents'];
    }
}
