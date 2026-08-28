<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\CollectiveIdentifiers\Create;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\DTOs\Requests\CollectiveIdentifiers\Invoice;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final class CreateRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    /**
     * @param array<int, Invoice> $invoices
     */
    public function __construct(
        public readonly array $invoices,
    ) {
        Validator::validate([
            'invoices' => $invoices,
        ], [
            'invoices' => [new MinRule(2)],
        ]);
    }
}
