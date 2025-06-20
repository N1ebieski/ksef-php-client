<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session;

use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentHandler;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Session\SessionResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedXmlRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\Terminate\TerminateHandler;
use N1ebieski\KSEFClient\Resources\AbstractResource;

final readonly class SessionResource extends AbstractResource implements SessionResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config
    ) {
    }

    public function authorisationChallenge(AuthorisationChallengeRequest | array $request): ResponseInterface
    {
        if ($request instanceof AuthorisationChallengeRequest == false) {
            $request = AuthorisationChallengeRequest::from($request);
        }

        return new AuthorisationChallengeHandler($this->client)->handle($request);
    }

    public function initToken(InitTokenRequest | array $request): ResponseInterface
    {
        if ($request instanceof InitTokenRequest == false) {
            $request = InitTokenRequest::from($request);
        }

        return new InitTokenHandler(
            client: $this->client,
            config: $this->config
        )->handle($request);
    }

    public function initSigned(InitSignedRequest | InitSignedXmlRequest | array $request): ResponseInterface
    {
        if (is_array($request)) {
            $request = InitSignedRequest::from($request);
        }

        return new InitSignedHandler(
            client: $this->client,
            signDocument: new SignDocumentHandler(),
            config: $this->config
        )->handle($request);
    }

    public function status(StatusRequest | array $request = new StatusRequest()): ResponseInterface
    {
        if (is_array($request)) {
            $request = StatusRequest::from($request);
        }

        return new StatusHandler($this->client)->handle($request);
    }

    public function terminate(): ResponseInterface
    {
        return new TerminateHandler($this->client)->handle();
    }
}
