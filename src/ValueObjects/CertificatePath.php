<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\File\ExistsRule;
use N1ebieski\KSEFClient\Validator\Rules\File\ExtensionsRule;
use N1ebieski\KSEFClient\Validator\Validator;
use SensitiveParameter;

final readonly class CertificatePath extends AbstractValueObject
{
    public string $path;

    public function __construct(
        string $path,
        #[SensitiveParameter]
        public ?string $passphrase = null
    ) {
        Validator::validate($path, [
            new ExistsRule(),
            new ExtensionsRule(['p12']),
        ]);

        $this->path = $path;
    }

    public static function from(string $path, ?string $passphrase = null): self
    {
        return new self($path, $passphrase);
    }
}
