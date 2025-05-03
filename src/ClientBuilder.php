<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient;

use Http\Discovery\Psr18ClientDiscovery;
use N1ebieski\KSEFClient\ClientHttp\ClientHttp;
use N1ebieski\KSEFClient\ClientHttp\DTOs\ConfigDTO;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\ClientHttp\ValueObjects\SessionToken;
use N1ebieski\KSEFClient\RequestBuilder;
use N1ebieski\KSEFClient\Resources\Online\Session\DTOs\AuthorisationChallengeDTO;
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

    private Mode $mode = Mode::PRODUCTION;

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
        $this->mode = $this->evaluate($mode, Mode::class);
        $this->apiUrl = $mode->getApiUrl();

        if ($mode->isEquals(Mode::TEST)) {
            $this->nip = new Nip('1111111111', skipValidation: true);
        }

        return $this;
    }

    public function withApiUrl(ApiUrl | string $apiUrl): self
    {
        $this->apiUrl = $this->evaluate($apiUrl, ApiUrl::class);

        return $this;
    }

    public function withApiToken(ApiToken | string $apiToken): self
    {
        $this->apiToken = $this->evaluate($apiToken, ApiToken::class);

        return $this;
    }

    public function withHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    public function withNip(Nip | string $nip): self
    {
        $this->nip = $this->evaluate($nip, Nip::class);

        return $this;
    }

    public function withPublicKeyPath(PublicKeyPath | string $publicKeyPath): self
    {
        $this->publicKeyPath = $this->evaluate($publicKeyPath, PublicKeyPath::class);

        return $this;
    }

    public function build(): Client
    {
        $client = new Client(new ClientHttp(
            client: $this->httpClient,
            configDTO: new ConfigDTO(new BaseUri($this->apiUrl->value))
        ));

        $response = $client->online()->session()->authorisationChallenge(new AuthorisationChallengeDTO($this->nip));

        return $client;
    }
}
