<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\HttpClient;

use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

interface ResponseInterface
{
    public BaseResponseInterface $baseResponse { get; }

    /**
     * @return array<string, mixed>
     */
    public function json(): array;

    public function object(): object;

    public function body(): string;
}
