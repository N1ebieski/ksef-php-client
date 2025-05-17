<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Common;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Common\CommonResourceInterface;
use N1ebieski\KSEFClient\Requests\Common\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Common\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Common\Status\StatusResponse;
use N1ebieski\KSEFClient\Resources\AbstractResource;

final readonly class CommonResource extends AbstractResource implements CommonResourceInterface
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    /**
     * @param StatusRequest|array<string, mixed> $request
     */
    public function status(StatusRequest | array $request): StatusResponse
    {
        if (is_array($request)) {
            $request = StatusRequest::from($request);
        }

        return new StatusHandler($this->client)->handle($request);
    }
}
