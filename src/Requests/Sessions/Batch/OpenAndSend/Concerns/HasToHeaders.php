<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns;

use N1ebieski\KSEFClient\Support\Optional;

/**
 * @property-read Optional | bool $subjectIdentifierValidation
 */
trait HasToHeaders
{
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
