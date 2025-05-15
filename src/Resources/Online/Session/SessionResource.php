<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session;

use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentHandler;
use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Session\SessionResourceInterface;
use N1ebieski\KSEFClient\Contracts\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedXmlRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenResponse;
use N1ebieski\KSEFClient\Requests\Online\Session\Terminate\TerminateHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;

final readonly class SessionResource extends AbstractResource implements SessionResourceInterface
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
            logXml: new LogXmlHandler($this->config),
            config: $this->config
        )->handle($dto);
    }

    public function initSigned(InitSignedRequest | InitSignedXmlRequest | array $dto): InitSignedResponse
    {
        if (is_array($dto)) {
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
