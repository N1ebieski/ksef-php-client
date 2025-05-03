<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Handlers;

use N1ebieski\KSEFClient\ClientHttp\DTOs\RequestDTO;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Method;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\Uri;
use N1ebieski\KSEFClient\Contracts\ClientHttpInterface;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeDTO;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\AuthorisationChallengeResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class AuthorisationChallengeHandler
{
    public function __construct(
        private ClientHttpInterface $client,
    ) {
    }

    public function handle(AuthorisationChallengeDTO $dto): AuthorisationChallengeResponse
    {
        $response = $this->client->sendRequest(new RequestDTO(
            method: Method::POST,
            uri: Uri::from('online/Session/AuthorisationChallenge'),
            data: [
                'contextIdentifier' => [
                    'type' => 'onip',
                    'identifier' => $dto->nip->value
                ]
            ]
        ));

        return AuthorisationChallengeResponse::from(
            json_decode($response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR)
        );
    }
}
