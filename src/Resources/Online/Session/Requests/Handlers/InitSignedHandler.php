<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Requests\Handlers;

use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentHandler;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlAction;
use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentAction;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;
use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Resources\Handler;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\InitSignedRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\InitSignedResponse;

final readonly class InitSignedHandler extends Handler
{
    public function __construct(
        private HttpClientInterface $client,
        private SignDocumentHandler $signDocument,
        private LogXmlHandler $logXml,
        private Config $config
    ) {
    }

    public function handle(InitSignedRequest $dto): InitSignedResponse
    {
        $signedXml = $this->signDocument->handle(
            new SignDocumentAction(
                certificate: CertificateFactory::make($dto->certificatePath),
                document: $dto->toXml()
            )
        );

        if ($this->config->logXmlPath instanceof LogXmlPath) {
            $this->logXml->handle(
                new LogXmlAction(
                    logXmlFilename: LogXmlFilename::from('init-signed.xml'),
                    document: $signedXml
                )
            );
        }

        $response = $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('online/Session/InitSigned'),
            headers: [
                new Header('Content-Type', 'application/octet-stream'),
            ],
            data: $signedXml
        ));

        return InitSignedResponse::fromResponse($response);
    }
}
