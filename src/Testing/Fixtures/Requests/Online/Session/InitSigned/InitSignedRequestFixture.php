<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitSigned;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class InitSignedRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'certificatePath' => [
            'path' => __DIR__ . '/../../../../../../../config/certificates/certificate.p12',
            'passphrase' => 'password',
        ],
        'initSessionSigned' => [
            'challenge' => '20250516-CR-70912D5DEF-7402B64FF3-CD',
            'timestamp' => '2022-01-01T00:00:00+01:00',
            'identifier' => '1111111111'
        ]
    ];

    public function withoutCertificatePath(): self
    {
        unset($this->data['certificatePath']);

        return $this;
    }
}
