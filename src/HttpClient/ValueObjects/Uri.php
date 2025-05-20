<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final readonly class Uri extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public function __construct(public string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function withParameters(string $parameters): self
    {
        return new self($this->value . '?' . $parameters);
    }

    public function withBaseUrl(BaseUri $baseUri): self
    {
        return $this->isUrl() ?
            $this : new self($baseUri->value->withSlashAtEnd()->value . $this->withoutSlashAtStart()->value);
    }

    public function withoutSlashAtStart(): self
    {
        return str_starts_with($this->value, '/') ? new self(ltrim($this->value, '/')) : $this;
    }

    public function withoutSlashAtEnd(): self
    {
        return str_ends_with($this->value, '/') ? new self(rtrim($this->value, '/')) : $this;
    }

    private function isUrl(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_URL) !== false;
    }
}
