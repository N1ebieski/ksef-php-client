<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Invoices;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\HeadersInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers\CollectiveIdentifierNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers\Invoices\PageSize;
use N1ebieski\KSEFClient\ValueObjects\Requests\ContinuationToken;

final class InvoicesRequest extends AbstractRequest implements BodyInterface, HeadersInterface, ParametersInterface
{
    /**
     * @param array<int, CollectiveIdentifierNumber> $collectiveIdentifierNumbers
     */
    public function __construct(
        public readonly array $collectiveIdentifierNumbers,
        public readonly Optional | PageSize $pageSize = new Optional(),
        public readonly Optional | ContinuationToken $continuationToken = new Optional(),
    ) {
        Validator::validate([
            'collectiveIdentifierNumbers' => $collectiveIdentifierNumbers,
        ], [
            'collectiveIdentifierNumbers' => [new MaxRule(10)],
        ]);
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['collectiveIdentifierNumbers']);
    }

    public function toHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            ...($this->continuationToken instanceof ContinuationToken ? [
                'x-continuation-token' => $this->continuationToken->value,
            ] : []),
        ];
    }

    public function toParameters(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['pageSize']);
    }
}
