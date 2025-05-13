<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\ValueObjects;

use BCMathExtended\BC;
use N1ebieski\KSEFClient\Support\ValueObject;
use OpenSSLAsymmetricKey;
use SensitiveParameter;

final readonly class Certificate extends ValueObject
{
    /**
     * @param array{issuer: array<string, string>, serialNumberHex: string} $info
     */
    public function __construct(
        #[SensitiveParameter]
        public string $raw,
        #[SensitiveParameter]
        public array $info,
        #[SensitiveParameter]
        public OpenSSLAsymmetricKey $privateKey
    ) {
    }

    public function getFingerPrint(): string
    {
        return base64_encode(hash('sha256', base64_decode($this->raw), true));
    }

    public function getSerialNumber(): string
    {
        return BC::hexdec($this->info['serialNumberHex']);
    }

    public function getIssuer(): string
    {
        $issuer = [];

        foreach ($this->info['issuer'] as $key => $value) {
            $issuer[] = $key . '=' . $value;
        }

        return implode(', ', array_reverse($issuer));
    }
}
