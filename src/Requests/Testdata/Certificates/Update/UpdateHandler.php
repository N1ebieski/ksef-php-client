<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Testdata\Certificates\Update;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\HttpClient\Request;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\Method;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\Uri;

final class UpdateHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(UpdateRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Put,
            uri: Uri::from(sprintf('testdata/certificates/%s', $request->serialNumber->value)),
            body: $request->toBody()
        ));
    }
}
