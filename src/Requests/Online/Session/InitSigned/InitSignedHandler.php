<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitSigned;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlAction;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentAction;
use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentHandler;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\Factories\EncryptedKeyFactory;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;

final readonly class InitSignedHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private SignDocumentHandler $signDocument,
        private LogXmlHandler $logXml,
        private Config $config
    ) {
    }

    public function handle(InitSignedRequest | InitSignedXmlRequest $request): ResponseInterface
    {
        $encryptedKey = null;

        if ($this->config->encryptionKey instanceof EncryptionKey) {
            $encryptedKey = EncryptedKeyFactory::make(
                encryptionKey: $this->config->encryptionKey,
                ksefPublicKeyPath: $this->config->ksefPublicKeyPath
            );
        }

        $signedXml = $request->toXml();

        if ($request instanceof InitSignedRequest) {
            $signedXml = $this->signDocument->handle(
                new SignDocumentAction(
                    certificate: CertificateFactory::make($request->certificatePath),
                    document: $request->toXml($encryptedKey?->toDom())
                )
            );
        }

        $this->logXml->handle(
            new LogXmlAction(
                logXmlFilename: LogXmlFilename::from('init-signed.xml'),
                document: $signedXml
            )
        );

        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('online/Session/InitSigned'),
            headers: [
                'Content-Type' => 'application/octet-stream',
            ],
            body: $signedXml
        ));
    }
}
