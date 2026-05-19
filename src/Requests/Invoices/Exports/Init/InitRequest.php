<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Invoices\Exports\Init;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Invoices\Exports\Filters;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\CompressionType;

final class InitRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly Filters $filters,
        public readonly Optional | bool $onlyMetadata = new Optional(),
        public readonly Optional | CompressionType $compressionType = new Optional()
    ) {
    }
}
