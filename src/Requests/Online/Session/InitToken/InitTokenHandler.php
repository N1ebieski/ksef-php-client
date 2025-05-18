<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitToken;

use N1ebieski\KSEFClient\Actions\LogXml\LogXmlAction;
use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Factories\EncryptedTokenFactory;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenResponse;
use N1ebieski\KSEFClient\ValueObjects\LogXmlFilename;
use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;

final readonly class InitTokenHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private LogXmlHandler $logXml
    ) {
    }

    public function handle(InitTokenRequest $request): InitTokenResponse
    {
        $encryptedToken = EncryptedTokenFactory::make(
            apiToken: $request->apiToken,
            timestamp: $request->timestamp,
            publicKeyPath: $request->ksefPublicKeyPath
        );

        $xml = $request->toXml($encryptedToken);

        $this->logXml->handle(
            new LogXmlAction(
                logXmlFilename: LogXmlFilename::from('init-token.xml'),
                document: $xml
            )
        );

        $response = $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('online/Session/InitToken'),
            headers: [
                new Header('Content-Type', 'application/octet-stream')
            ],
            data: $xml
        ));

        return InitTokenResponse::fromResponse($response);
    }
}
