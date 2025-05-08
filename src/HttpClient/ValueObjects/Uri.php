<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\HttpClient\ValueObjects;

use N1ebieski\KSEFClient\Contracts\ValueAwareInterface;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\Support\ValueObject;
use Stringable;

final readonly class Uri extends ValueObject implements ValueAwareInterface, Stringable
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

    public function withParameters(array $parameters): self
    {
        return $parameters === [] ? $this : new self($this->value . '?' . http_build_query($parameters));
    }

    public function withBaseUrl(BaseUri $baseUri): self
    {
        return $this->isUrl() ?
            $this : new self($baseUri->value->withSlashAtEnd()->value . $this->withoutSlashAtStart()->value);
    }

    public function withoutSlashAtStart(): self
    {
        return str_starts_with($this->value, '/') ? new self(substr($this->value, 0, -1)) : $this;
    }

    private function isUrl(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_URL) !== false;
    }
}
