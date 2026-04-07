<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\HttpClient;

use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

/**
 * @property-read BaseResponseInterface $baseResponse
 */
interface ResponseInterface extends ArrayableInterface
{
    public function throwExceptionIfError(): void;

    public function status(): int;

    public function header(string $name): ?string;

    /**
     * @return array<string, array<int, string>>
     */
    public function headers(): array;

    /**
     * @return array<string, mixed>
     */
    public function json(): array;

    /**
     * @return object|array<string, mixed>
     */
    public function object(): object | array;

    /**
     * @return string|array<string, mixed>
     */
    public function data(): string | array;

    public function body(): string;
}
