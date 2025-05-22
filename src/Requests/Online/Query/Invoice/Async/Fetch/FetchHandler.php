<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Fetch;

use GuzzleHttp\Psr7\Utils;
use N1ebieski\KSEFClient\Actions\DecryptDocument\DecryptDocumentAction;
use N1ebieski\KSEFClient\Actions\DecryptDocument\DecryptDocumentHandler;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\DecryptedResponse;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;

final readonly class FetchHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private DecryptDocumentHandler $decryptDocument,
        private Config $config
    ) {
    }

    public function handle(FetchRequest $request): ResponseInterface
    {
        $response = $this->client->sendRequest(new Request(
            method: Method::Get,
            headers: [
                new Header('Accept', 'application/octet-stream')
            ],
            uri: Uri::from(
                sprintf(
                    'online/Query/Invoice/Async/Fetch/%s/%s',
                    $request->queryElementReferenceNumber->value,
                    $request->partElementReferenceNumber->value
                )
            ),
        ));

        if ($this->config->encryptionKey instanceof EncryptionKey) {
            $decryptedBody = $this->decryptDocument->handle(new DecryptDocumentAction(
                encryptionKey: $this->config->encryptionKey,
                document: $response->body()
            ));

            $response = new DecryptedResponse($response, $decryptedBody);
        }

        return $response;
    }
}
