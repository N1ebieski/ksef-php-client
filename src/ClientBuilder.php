<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient;

use Http\Discovery\Psr18ClientDiscovery;
use N1ebieski\KSEFClient\Actions\EncryptTokenAction;
use N1ebieski\KSEFClient\Actions\Handlers\EncryptTokenHandler;
use N1ebieski\KSEFClient\HttpClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\HttpClient;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\InitTokenRequest;
use N1ebieski\KSEFClient\Resources\RootResource;
use N1ebieski\KSEFClient\Support\Evaluation\Evaluation;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use N1ebieski\KSEFClient\ValueObjects\ApiUrl;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\PublicKeyPath;
use Psr\Http\Client\ClientInterface;

final class ClientBuilder
{
    private ClientInterface $httpClient;

    private Mode $mode = Mode::Production;

    private ApiUrl $apiUrl;

    private ApiToken $apiToken;

    private NIP $nip;

    private PublicKeyPath $publicKeyPath;

    public function __construct()
    {
        $this->httpClient = Psr18ClientDiscovery::find();
        $this->apiUrl = $this->mode->getApiUrl();
    }

    public function withMode(Mode | string $mode): self
    {
        if ($mode instanceof Mode === false) {
            $mode = Mode::from($mode);
        }

        $this->mode = $mode;

        $this->apiUrl = $this->mode->getApiUrl();

        if ($this->mode->isEquals(Mode::Test)) {
            $this->nip = new NIP('1111111111');
        }

        return $this;
    }

    public function withApiUrl(ApiUrl | string $apiUrl): self
    {
        if ($apiUrl instanceof ApiUrl === false) {
            $apiUrl = ApiUrl::from($apiUrl);
        }

        $this->apiUrl = $apiUrl;

        return $this;
    }

    public function withApiToken(ApiToken | string $apiToken): self
    {
        if ($apiToken instanceof ApiToken === false) {
            $apiToken = ApiToken::from($apiToken);
        }

        $this->apiToken = $apiToken;

        return $this;
    }

    public function withHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    public function withNip(NIP | string $nip): self
    {
        if ($nip instanceof NIP === false) {
            $nip = NIP::from($nip);
        }

        $this->nip = $nip;

        return $this;
    }

    public function withPublicKeyPath(PublicKeyPath | string $publicKeyPath): self
    {
        if ($publicKeyPath instanceof PublicKeyPath === false) {
            $publicKeyPath = PublicKeyPath::from($publicKeyPath);
        }

        $this->publicKeyPath = $publicKeyPath;

        return $this;
    }

    public function build(): RootResource
    {
        $config = new Config(new BaseUri($this->apiUrl->value));
        $httpClient = new HttpClient(
            client: $this->httpClient,
            config: $config
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

        return new RootResource($httpClient->withConfig(
            $config->withSessionToken(
                $initTokenResponse->sessionToken->token
            )
        ));
    }
}
