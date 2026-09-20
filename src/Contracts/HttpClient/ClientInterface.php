<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\HttpClient;

use Psr\Http\Client\ClientInterface as BaseClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface extends BaseClientInterface
{
    /**
     * Sends async a PSR-7 requests and returns a PSR-7 responses.
     *
     * @param array<int, RequestInterface> $requests
     *
     * @return array<int|string, ResponseInterface|null>
     */
    public function sendAsyncRequest(array $requests, int $concurrency = 8): array;
}
