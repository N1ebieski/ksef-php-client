<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Handlers;

use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlAction;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;
use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\DTOs\Config;
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
        private LogXmlHandler $logXml,
        private Config $config
    ) {
    }

    public function handle(SendRequest $dto): SendResponse
    {
        $xml = $dto->toXml();

        $hashSHA = base64_encode(hash('sha256', $xml, true));
        $invoiceBody = base64_encode($xml);
        $fileSize = strlen($xml);

        if ($this->config->logXmlPath instanceof LogXmlPath) {
            $this->logXml->handle(
                new LogXmlAction(
                    logXmlFilename: LogXmlFilename::from('send-invoice.xml'),
                    document: $xml
                )
            );
        }

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
