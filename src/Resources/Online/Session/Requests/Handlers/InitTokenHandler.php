<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Requests\Handlers;

use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;
use N1ebieski\KSEFClient\Actions\EncryptTokenAction;
use N1ebieski\KSEFClient\Actions\Handlers\EncryptTokenHandler;
use N1ebieski\KSEFClient\Actions\Handlers\LogXmlHandler;
use N1ebieski\KSEFClient\Actions\LogXmlAction;
use N1ebieski\KSEFClient\Actions\ValueObjects\LogXmlFilename;
use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Header;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Resources\Handler;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\InitTokenResponse;

final readonly class InitTokenHandler extends Handler
{
    public function __construct(
        private HttpClientInterface $client,
        private EncryptTokenHandler $encryptToken,
        private LogXmlHandler $logXml,
        private Config $config
    ) {
    }

    public function handle(InitTokenRequest $dto): InitTokenResponse
    {
        $encryptedToken = $this->encryptToken->handle(
            new EncryptTokenAction(
                apiToken: $dto->apiToken,
                timestamp: $dto->timestamp,
                publicKeyPath: $dto->publicKeyPath
            )
        );

        $xml = $dto->toXml($encryptedToken);

        if ($this->config->logXmlPath instanceof LogXmlPath) {
            $this->logXml->handle(
                new LogXmlAction(
                    logXmlFilename: LogXmlFilename::from('init-token.xml'),
                    document: $xml
                )
            );
        }

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
