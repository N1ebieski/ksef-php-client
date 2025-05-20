<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\Status\Status;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class StatusRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'referenceNumber' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD',
        'pageSize' => 10,
        'pageOffset' => 0,
        'includeDetails' => true
    ];

    public function withoutReferenceNumber(): self
    {
        unset($this->data['referenceNumber']);

        return $this;
    }

    public function withoutPageSize(): self
    {
        unset($this->data['pageSize']);

        return $this;
    }

    public function withoutPageOffset(): self
    {
        unset($this->data['pageOffset']);

        return $this;
    }

    public function withoutIncludeDetails(): self
    {
        unset($this->data['includeDetails']);

        return $this;
    }
}
