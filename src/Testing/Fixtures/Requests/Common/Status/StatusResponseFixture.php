<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Common\Status;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class StatusResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'processingCode' => 801,
        'processingDescription' => 'magn',
        'referenceNumber' => '20841003-T2-61EDCAEF30-90EE406BFB-A2',
        'timestamp' => '2022-01-01T00:00:00+01:00',
        'upo' => 'base64upostring'
    ];

    public function withUpo(): self
    {
        $this->data['upo'] = 'base64upostring';

        return $this;
    }

    public function withoutUpo(): self
    {
        $this->data['upo'] = null;

        return $this;
    }
}
