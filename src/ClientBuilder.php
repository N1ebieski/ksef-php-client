<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient;

use Http\Discovery\Psr18ClientDiscovery;
use N1ebieski\KSEFClient\Actions\DTOs\EncryptTokenAction;
use N1ebieski\KSEFClient\Actions\Handlers\EncryptTokenHandler;
use N1ebieski\KSEFClient\ClientHttp\ClientHttp;
use N1ebieski\KSEFClient\ClientHttp\DTOs\ConfigDTO;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\SessionToken;
use N1ebieski\KSEFClient\RequestBuilder;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\InitTokenRequest;
use N1ebieski\KSEFClient\Support\Concerns\HasEvaluation;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use N1ebieski\KSEFClient\ValueObjects\ApiUrl;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\Nip;
use N1ebieski\KSEFClient\ValueObjects\PublicKeyPath;
use N1ebieski\KSEFClient\ValueObjects\Url;
use Psr\Http\Client\ClientInterface;

final class ClientBuilder
{
    use HasEvaluation;

    private ClientInterface $httpClient;

    private Mode $mode = Mode::Production;

    private ApiUrl $apiUrl;

    private ApiToken $apiToken;

    private Nip $nip;

    private PublicKeyPath $publicKeyPath;

    public function __construct()
    {
        $this->httpClient = Psr18ClientDiscovery::find();
        $this->apiUrl = $this->mode->getApiUrl();
    }

    public function withMode(Mode | string $mode): self
    {
        /** @var Mode $mode */
        $mode = $this->evaluate($mode, Mode::class);

        $this->mode = $mode;
        $this->apiUrl = $this->mode->getApiUrl();

        if ($this->mode->isEquals(Mode::Test)) {
            $this->nip = new Nip('1111111111', skipValidation: true);
        }

        return $this;
    }

    public function withApiUrl(ApiUrl | string $apiUrl): self
    {
        /** @var ApiUrl $apiUrl */
        $apiUrl = $this->evaluate($apiUrl, ApiUrl::class);

        $this->apiUrl = $apiUrl;

        return $this;
    }

    public function withApiToken(ApiToken | string $apiToken): self
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->evaluate($apiToken, ApiToken::class);

        $this->apiToken = $apiToken;

        return $this;
    }

    public function withHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    public function withNip(Nip | string $nip): self
    {
        /** @var Nip $nip */
        $nip = $this->evaluate($nip, Nip::class);

        $this->nip = $nip;

        return $this;
    }

    public function withPublicKeyPath(PublicKeyPath | string $publicKeyPath): self
    {
        /** @var PublicKeyPath $publicKeyPath */
        $publicKeyPath = $this->evaluate($publicKeyPath, PublicKeyPath::class);

        $this->publicKeyPath = $publicKeyPath;

        return $this;
    }

    public function build(): Client
    {
        $configDTO = new ConfigDTO(new BaseUri($this->apiUrl->value));
        $clientHttp = new ClientHttp(
            client: $this->httpClient,
            configDTO: $configDTO
        );

        $client = new Client($clientHttp);

        $authorisationChallengeResponse = $client->online()->session()->authorisationChallenge(
            new AuthorisationChallengeRequest($this->nip)
        );

        $encryptedToken = new EncryptTokenHandler()->handle(
            new EncryptTokenAction(
                apiToken: $this->apiToken,
                timestamp: $authorisationChallengeResponse->timestamp,
                publicKeyPath: $this->publicKeyPath
            )
        );

        $initTokenResponse = $client->online()->session()->initToken(
            new InitTokenRequest(
                encryptedToken: $encryptedToken,
                challenge: $authorisationChallengeResponse->challenge,
                nip: $this->nip
            )
        );

        return new Client($clientHttp->withConfigDTO(
            $configDTO->withSessionToken(
                $initTokenResponse->sessionToken->token
            )
        ));
    }
}
