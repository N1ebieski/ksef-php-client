<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Resources\Responses;

use N1ebieski\KSEFClient\Testing\Fixtures\Resources\ResponseFixture;

final class ErrorResponseFixture extends ResponseFixture
{
    public int $statusCode = 400;

    /**
     * @var array<string, mixed>
     */
    public array $contents = [
        'exception' => [
            'serviceCtx' => 'srvDVAKA',
            'serviceCode' => '20211001-EX-FFFFFFFFFF-FFFFFFFFFF-FF',
            'serviceName' => 'online.session.authorisation.challenge',
            'timestamp' => '2021-10-01T12:13:14.999Z',
            'referenceNumber' => '20211001-SE-FFFFFFFFFF-FFFFFFFFFF-FF',
            'exceptionDetailList' => [
                [
                    'exceptionCode' => 12345,
                    'exceptionDescription' => 'Opis błędu.'
                ]
            ]
        ]
    ];

    public function getDataAsContext(): object
    {
        return (object) $this->contents;
    }
}
