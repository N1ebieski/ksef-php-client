<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient;

use Http\Discovery\Psr18ClientDiscovery;
use N1ebieski\KSEFClient\Actions\DTOs\EncryptTokenAction;
use N1ebieski\KSEFClient\Actions\Handlers\EncryptTokenHandler;
use N1ebieski\KSEFClient\HttpClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\HttpClient;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\RootResource;
use N1ebieski\KSEFClient\Support\Evaluation\Evaluation;
use N1ebieski\KSEFClient\Support\Evaluation\ValueObjects\ObjectNamespace;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use N1ebieski\KSEFClient\ValueObjects\ApiUrl;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\Nip;
use N1ebieski\KSEFClient\ValueObjects\PublicKeyPath;
use Psr\Http\Client\ClientInterface;

final class ClientBuilder
{
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
        $mode = Evaluation::evaluate($mode, ObjectNamespace::from(Mode::class));

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
        $apiUrl = Evaluation::evaluate($apiUrl, ObjectNamespace::from(ApiUrl::class));

        $this->apiUrl = $apiUrl;

        return $this;
    }

    public function withApiToken(ApiToken | string $apiToken): self
    {
        /** @var ApiToken $apiToken */
        $apiToken = Evaluation::evaluate($apiToken, ObjectNamespace::from(ApiToken::class));

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
        $nip = Evaluation::evaluate($nip, ObjectNamespace::from(Nip::class));

        $this->nip = $nip;

        return $this;
    }

    public function withPublicKeyPath(PublicKeyPath | string $publicKeyPath): self
    {
        /** @var PublicKeyPath $publicKeyPath */
        $publicKeyPath = Evaluation::evaluate($publicKeyPath, ObjectNamespace::from(PublicKeyPath::class));

        $this->publicKeyPath = $publicKeyPath;

        return $this;
    }

    public function build(): RootResource
    {
        $configDTO = new Config(new BaseUri($this->apiUrl->value));
        $httpClient = new HttpClient(
            client: $this->httpClient,
            configDTO: $configDTO
        );

        $client = new RootResource($httpClient);

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

        return new RootResource($httpClient->withConfigDTO(
            $configDTO->withSessionToken(
                $initTokenResponse->sessionToken->token
            )
        ));
    }
}
