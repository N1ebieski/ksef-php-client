<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\Status\Status;

use N1ebieski\KSEFClient\Testing\Fixtures\AbstractFixture;

final class StatusRequestFixture extends AbstractFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'reference_number' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD',
        'page_size' => 10,
        'page_offset' => 0,
        'include_details' => true
    ];

    public function withoutReferenceNumber(): self
    {
        unset($this->data['reference_number']);

        return $this;
    }

    public function withoutPageSize(): self
    {
        unset($this->data['page_size']);

        return $this;
    }

    public function withoutPageOffset(): self
    {
        unset($this->data['page_offset']);

        return $this;
    }

    public function withoutIncludeDetails(): self
    {
        unset($this->data['include_details']);

        return $this;
    }
}
