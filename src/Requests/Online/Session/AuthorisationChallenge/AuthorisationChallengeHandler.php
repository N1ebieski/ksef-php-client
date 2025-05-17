<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\HttpClient\DTOs\Request;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Method;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeResponse;

final readonly class AuthorisationChallengeHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(AuthorisationChallengeRequest $request): AuthorisationChallengeResponse
    {
        $response = $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('online/Session/AuthorisationChallenge'),
            data: [
                'contextIdentifier' => [
                    'type' => 'onip',
                    'identifier' => $request->nip->value
                ]
            ]
        ));

        return AuthorisationChallengeResponse::fromResponse($response);
    }
}
