<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session;

use N1ebieski\KSEFClient\Actions\Handlers\EncryptTokenHandler;
use N1ebieski\KSEFClient\Actions\Handlers\LogXmlHandler;
use N1ebieski\KSEFClient\Actions\Handlers\SignDocumentHandler;
use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Session\SessionResourceInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Handlers\AuthorisationChallengeHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Handlers\InitSignedHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Handlers\InitTokenHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Handlers\TerminateHandler;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\InitSignedRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\InitSignedResponse;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\InitTokenResponse;
use N1ebieski\KSEFClient\Resources\Resource;

final readonly class SessionResource extends Resource implements SessionResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config
    ) {
    }

    public function authorisationChallenge(AuthorisationChallengeRequest | array $dto): AuthorisationChallengeResponse
    {
        if ($dto instanceof AuthorisationChallengeRequest == false) {
            $dto = AuthorisationChallengeRequest::from($dto);
        }

        return new AuthorisationChallengeHandler($this->client)->handle($dto);
    }

    public function initToken(InitTokenRequest | array $dto): InitTokenResponse
    {
        if ($dto instanceof InitTokenRequest == false) {
            $dto = InitTokenRequest::from($dto);
        }

        return new InitTokenHandler(
            client: $this->client,
            encryptToken: new EncryptTokenHandler(),
            logXml: new LogXmlHandler($this->config),
            config: $this->config
        )->handle($dto);
    }

    public function initSigned(InitSignedRequest | array $dto): InitSignedResponse
    {
        if ($dto instanceof InitSignedRequest == false) {
            $dto = InitSignedRequest::from($dto);
        }

        return new InitSignedHandler(
            client: $this->client,
            signDocument: new SignDocumentHandler(),
            logXml: new LogXmlHandler($this->config),
            config: $this->config
        )->handle($dto);
    }

    public function terminate(): ResponseInterface
    {
        return new TerminateHandler($this->client)->handle();
    }
}
