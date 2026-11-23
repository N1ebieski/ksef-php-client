<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Online\Open;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\HeadersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

final class OpenRequest extends AbstractRequest implements BodyInterface, HeadersInterface
{
    public function __construct(
        public readonly FormCode $formCode,
        public readonly Optional | bool $subjectIdentifierValidation = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        return [
            'formCode' => [
                'systemCode' => $this->formCode->value,
                'schemaVersion' => $this->formCode->getSchemaVersion(),
                'value' => $this->formCode->getValue(),
            ]
        ];
    }

    public function toHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($this->subjectIdentifierValidation === true) {
            $headers['X-KSeF-Feature'] = 'subject-identifier-validation';
        }

        return $headers;
    }
}
