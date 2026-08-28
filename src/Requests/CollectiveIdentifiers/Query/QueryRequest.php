<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Query;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\HeadersInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Validator\Rules\Date\MaxRangeRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers\CollectiveIdentifierNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers\DateCreatedFrom;
use N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers\DateCreatedTo;
use N1ebieski\KSEFClient\ValueObjects\Requests\CollectiveIdentifiers\PageSize;
use N1ebieski\KSEFClient\ValueObjects\Requests\ContinuationToken;

final class QueryRequest extends AbstractRequest implements BodyInterface, HeadersInterface, ParametersInterface
{
    public readonly DateCreatedFrom $dateCreatedFrom;

    public readonly DateCreatedTo $dateCreatedTo;

    public function __construct(
        DateCreatedFrom $dateCreatedFrom,
        DateCreatedTo $dateCreatedTo,
        public readonly Optional | CollectiveIdentifierNumber $collectiveIdentifierNumber = new Optional(),
        public readonly Optional | int $invoiceCountFrom = new Optional(),
        public readonly Optional | int $invoiceCountTo = new Optional(),
        public readonly Optional | bool $createdInCurrentContext = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
        public readonly Optional | ContinuationToken $continuationToken = new Optional(),
    ) {
        Validator::validate([
            'dateCreatedFrom' => $dateCreatedFrom->value
        ], [
            'dateCreatedFrom' => [
                new MaxRangeRule($dateCreatedTo->value, 100)
            ],
        ]);

        $this->dateCreatedFrom = $dateCreatedFrom;
        $this->dateCreatedTo = $dateCreatedTo;
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: [
            'collectiveIdentifierNumber',
            'dateCreatedFrom',
            'dateCreatedTo',
            'invoiceCountFrom',
            'invoiceCountTo',
            'createdInCurrentContext',
        ]);
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
