<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Send;

use N1ebieski\KSEFClient\Actions\LogXml\LogXmlAction;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendResponse;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;
use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;

final readonly class SendHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private LogXmlHandler $logXml
    ) {
    }

    public function handle(SendRequest $request): SendResponse
    {
        $xml = $request->toXml();

        $hashSHA = base64_encode(hash('sha256', $xml, true));
        $invoiceBody = base64_encode($xml);
        $fileSize = strlen($xml);

        $this->logXml->handle(
            new LogXmlAction(
                logXmlFilename: LogXmlFilename::from('send-invoice.xml'),
                document: $xml
            )
        );

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
