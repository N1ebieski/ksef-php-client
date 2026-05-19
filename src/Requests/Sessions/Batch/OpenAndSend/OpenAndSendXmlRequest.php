<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns\HasToBody;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\CompressionType;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

final class OpenAndSendXmlRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    /**
     * @var array<int, string> $faktury
     */
    public readonly array $faktury;

    /**
     * @param array<int, string> $faktury
     */
    public function __construct(
        public readonly FormCode $formCode,
        array $faktury,
        public readonly Optional | bool $offlineMode = new Optional(),
        public readonly Optional | CompressionType $compressionType = new Optional()
    ) {
        Validator::validate([
            'faktury' => $faktury,
        ], [
            'faktury' => [
                new MinRule(1),
                new MaxRule(10000)
            ]
        ]);

        $this->faktury = $faktury;
    }
}
