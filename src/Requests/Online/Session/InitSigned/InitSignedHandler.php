<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitSigned;

use N1ebieski\KSEFClient\Actions\LogXml\LogXmlAction;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentAction;
use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentHandler;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedResponse;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;

final readonly class InitSignedHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private SignDocumentHandler $signDocument,
        private LogXmlHandler $logXml
    ) {
    }

    public function handle(InitSignedRequest | InitSignedXmlRequest $request): InitSignedResponse
    {
        $signedXml = match (true) {
            $request instanceof InitSignedRequest => value(function () use ($request): string {
                if ($request->certificatePath === null) {
                    throw new \InvalidArgumentException('Certificate path is required for this request.');
                }

                return $this->signDocument->handle(
                    new SignDocumentAction(
                        certificate: CertificateFactory::make($request->certificatePath),
                        document: $request->toXml()
                    )
                );
            }),
            default => $request->toXml(),
        };

        $this->logXml->handle(
            new LogXmlAction(
                logXmlFilename: LogXmlFilename::from('init-signed.xml'),
                document: $signedXml
            )
        );

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
