<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitToken;

use DOMDocument;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\DTOs\InitSessionToken;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\EncryptedToken;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use SensitiveParameter;

final readonly class InitTokenRequest extends AbstractRequest
{
    public function __construct(
        #[SensitiveParameter]
        public ApiToken $apiToken,
        public InitSessionToken $initSessionToken
    ) {
    }

    public function toXml(EncryptedToken $encryptedToken, ?DOMDocument $encryptionDom = null): string
    {
        return $this->initSessionToken->toXml($encryptedToken, $encryptionDom);
    }
}
