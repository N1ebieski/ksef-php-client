<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Handlers;

use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Resources\Handler;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\SendResponse;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\SendRequest;

final readonly class SendHandler extends Handler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(SendRequest $dto): SendResponse
    {
        $xml = $dto->toXml();

        $hashSHA = base64_encode(hash('sha256', $xml, true));
        $invoiceBody = base64_encode($xml);
        $fileSize = strlen($xml);

        $response = $this->client->sendRequest(new Request(
            method: Method::Put,
            uri: Uri::from('online/Invoice/Send'),
            data: [
                'invoiceHash' => [
                    'fileSize' => $fileSize,
                    'hashSHA' => [
                        'algorithm' => 'SHA-256',
                        'encoding' => 'Base64',
                        'value' => $hashSHA,
                    ],
                ],
                'invoicePayload' => [
                    'type' => 'plain',
                    'invoiceBody' => $invoiceBody
                ]
            ]
        ));

        return SendResponse::fromResponse($response);
    }
}
