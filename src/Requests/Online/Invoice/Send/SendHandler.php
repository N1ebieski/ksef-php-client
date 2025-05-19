<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\Send;

use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\Actions\EncryptDocument\EncryptDocumentAction;
use N1ebieski\KSEFClient\Actions\EncryptDocument\EncryptDocumentHandler;
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
use N1ebieski\KSEFClient\Requests\ValueObjects\Type;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;

final readonly class SendHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private LogXmlHandler $logXml,
        private EncryptDocumentHandler $encryptDocument,
        private Config $config
    ) {
    }

    public function handle(SendRequest $request): SendResponse
    {
        $xml = $request->toXml();

        if ($this->config->encryptionKey instanceof EncryptionKey) {
            $encryptedXml = $this->encryptDocument->handle(new EncryptDocumentAction(
                encryption: $this->config->encryptionKey,
                document: $xml
            ));
        }

        $this->logXml->handle(new LogXmlAction(
            logXmlFilename: LogXmlFilename::from('send-invoice.xml'),
            document: $xml
        ));

        $response = $this->client->sendRequest(new Request(
            method: Method::Put,
            uri: Uri::from('online/Invoice/Send'),
            data: [
                'invoiceHash' => Utility::hash($xml),
                'invoicePayload' => isset($encryptedXml) ? [
                    'type' => Type::Encrypted->value,
                    'encryptedInvoiceHash' => Utility::hash($encryptedXml),
                    'encryptedInvoiceBody' => base64_encode($encryptedXml)
                ] : [
                    'type' => Type::Plain->value,
                    'invoiceBody' => base64_encode($xml)
                ]
            ]
        ));

        return SendResponse::fromResponse($response);
    }
}
