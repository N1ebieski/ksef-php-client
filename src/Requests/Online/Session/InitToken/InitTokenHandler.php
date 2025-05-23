<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitToken;

use N1ebieski\KSEFClient\Actions\LogXml\LogXmlAction;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Factories\EncryptedKeyFactory;
use N1ebieski\KSEFClient\Factories\EncryptedTokenFactory;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;

final readonly class InitTokenHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private LogXmlHandler $logXml,
        private Config $config
    ) {
    }

    public function handle(InitTokenRequest $request): ResponseInterface
    {
        $encryptedToken = EncryptedTokenFactory::make(
            apiToken: $request->apiToken,
            timestamp: $request->timestamp,
            ksefPublicKeyPath: $this->config->ksefPublicKeyPath
        );

        $encryptedKey = null;

        if ($this->config->encryptionKey instanceof EncryptionKey) {
            $encryptedKey = EncryptedKeyFactory::make(
                encryptionKey: $this->config->encryptionKey,
                ksefPublicKeyPath: $this->config->ksefPublicKeyPath
            );
        }

        $xml = $request->toXml($encryptedToken, $encryptedKey?->toDom());

        $this->logXml->handle(
            new LogXmlAction(
                logXmlFilename: LogXmlFilename::from('init-token.xml'),
                document: $xml
            )
        );

        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('online/Session/InitToken'),
            headers: [
                'Content-Type' => 'application/octet-stream'
            ],
            body: $xml
        ));
    }
}
