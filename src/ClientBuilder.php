<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient;

use DateTimeImmutable;
use Http\Discovery\Psr18ClientDiscovery;
use InvalidArgumentException;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\HttpClient\DTOs\Config as HttpClientConfig;
use N1ebieski\KSEFClient\HttpClient\HttpClient;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\SessionToken;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\Challenge;
use N1ebieski\KSEFClient\Resources\ClientResource;
use N1ebieski\KSEFClient\Validator\Rules\String\MaxBytesRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MinBytesRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use N1ebieski\KSEFClient\ValueObjects\ApiUrl;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\KSEFPublicKeyPath;
use N1ebieski\KSEFClient\ValueObjects\LogXmlPath;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use Psr\Http\Client\ClientInterface;

final class ClientBuilder
{
    private ClientInterface $httpClient;

    private Mode $mode = Mode::Production;

    private ApiUrl $apiUrl;

    private ?ApiToken $apiToken = null;

    private ?SessionToken $sessionToken = null;

    private ?CertificatePath $certificatePath = null;

    private NIP $nip;

    private KSEFPublicKeyPath $ksefPublicKeyPath;

    private ?LogXmlPath $logXmlPath = null;

    private ?EncryptionKey $encryptionKey = null;

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

    public function withEncryptionKey(EncryptionKey | string $encryptionKey, ?string $iv = null): self
    {
        if (is_string($encryptionKey)) {
            if ($iv === null) {
                throw new InvalidArgumentException('IV is required when key is string.');
            }

            Validator::validate([
                'key' => $encryptionKey,
                'iv' => $iv
            ], [
                'key' => [new MinBytesRule(32), new MaxBytesRule(32)],
                'iv' => [new MinBytesRule(16), new MaxBytesRule(16)]
            ]);

            $encryptionKey = new EncryptionKey($encryptionKey, $iv);
        }

        $this->encryptionKey = $encryptionKey;

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

        $this->certificatePath = null;

        $this->apiToken = $apiToken;

        return $this;
    }

    public function withSessionToken(SessionToken | string $sessionToken): self
    {
        if ($sessionToken instanceof SessionToken === false) {
            $sessionToken = SessionToken::from($sessionToken);
        }

        $this->certificatePath = null;
        $this->apiToken = null;

        $this->sessionToken = $sessionToken;

        return $this;
    }

    public function withCertificatePath(CertificatePath | string $certificatePath, ?string $passphrase = null): self
    {
        if ($certificatePath instanceof CertificatePath === false) {
            $certificatePath = CertificatePath::from($certificatePath, $passphrase);
        }

        $this->apiToken = null;

        $this->certificatePath = $certificatePath;

        return $this;
    }

    public function withHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    public function withNIP(NIP | string $nip): self
    {
        if ($nip instanceof NIP === false) {
            $nip = NIP::from($nip);
        }

        $this->nip = $nip;

        return $this;
    }

    public function withKSEFPublicKeyPath(KSEFPublicKeyPath | string $publicKeyPath): self
    {
        if ($publicKeyPath instanceof KSEFPublicKeyPath === false) {
            $publicKeyPath = KSEFPublicKeyPath::from($publicKeyPath);
        }

        $this->ksefPublicKeyPath = $publicKeyPath;

        return $this;
    }

    public function withLogXmlPath(LogXmlPath | string $logXmlPath): self
    {
        if ($logXmlPath instanceof LogXmlPath === false) {
            $logXmlPath = LogXmlPath::from($logXmlPath);
        }

        $this->logXmlPath = $logXmlPath;

        return $this;
    }

    public function build(): ClientResource
    {
        $config = new Config(
            logXmlPath: $this->logXmlPath,
            encryptionKey: $this->encryptionKey,
            ksefPublicKeyPath: $this->ksefPublicKeyPath,
        );

        $httpClientConfig = new HttpClientConfig(
            baseUri: new BaseUri($this->apiUrl->value)
        );

        $httpClient = new HttpClient(
            client: $this->httpClient,
            config: $httpClientConfig
        );

        $client = new ClientResource($httpClient, $config);

        if ($this->sessionToken instanceof SessionToken) {
            return $client->withSessionToken($this->sessionToken);
        }

        if ($this->isAuthorisation()) {
            /** @var object{challenge: string, timestamp: string} $authorisationChallengeResponse */
            $authorisationChallengeResponse = $client->online()->session()->authorisationChallenge(
                new AuthorisationChallengeRequest($this->nip)
            )->object();

            $authorisationSessionResponse = match (true) { //@phpstan-ignore-line
                $this->apiToken instanceof ApiToken => $client->online()->session()->initToken(
                    new InitTokenRequest(
                        apiToken: $this->apiToken,
                        challenge: Challenge::from($authorisationChallengeResponse->challenge),
                        timestamp: new DateTimeImmutable($authorisationChallengeResponse->timestamp),
                        nip: $this->nip
                    )
                ),
                $this->certificatePath instanceof CertificatePath => $client->online()->session()->initSigned(
                    new InitSignedRequest(
                        certificatePath: $this->certificatePath,
                        challenge: Challenge::from($authorisationChallengeResponse->challenge),
                        timestamp: new DateTimeImmutable($authorisationChallengeResponse->timestamp),
                        nip: $this->nip
                    )
                )
            };

            /** @var object{sessionToken: object{token: string}} $authorisationSessionResponse */
            $authorisationSessionResponse = $authorisationSessionResponse->object();

            return $client->withSessionToken($authorisationSessionResponse->sessionToken->token);
        }

        return $client;
    }

    private function isAuthorisation(): bool
    {
        return ! $this->sessionToken instanceof SessionToken && (
            $this->apiToken instanceof ApiToken || $this->certificatePath instanceof CertificatePath
        );
    }
}
