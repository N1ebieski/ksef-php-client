<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\Status;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;

final readonly class StatusHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(StatusRequest $request = new StatusRequest()): StatusResponse
    {
        $response = $this->client->sendRequest(new Request(
            method: Method::Get,
            uri: Uri::from(
                sprintf('online/Session/Status/%s', $request->referenceNumber->value ?? '')
            ),
            data: [
                'PageSize' => $request->pageSize,
                'PageOffset' => $request->pageOffset,
                'IncludeDetails' => $request->includeDetails
            ]
        ));

        return StatusResponse::fromResponse($response);
    }
}
