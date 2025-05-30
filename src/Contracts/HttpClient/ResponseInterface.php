<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\HttpClient;

use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

interface ResponseInterface extends ArrayableInterface
{
    public BaseResponseInterface $baseResponse { get; }

    public function status(): int;

    /**
     * @return array<string, mixed>
     */
    public function json(): array;

    public function object(): object;

    public function body(): string;

    public function toArray(KeyType $keyType = KeyType::Camel): array;
}
