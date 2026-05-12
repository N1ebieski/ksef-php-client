<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient;

use CuyZ\Valinor\Cache\Cache;
use DateTimeImmutable;
use DateTimeInterface;
use Http\Discovery\Psr18ClientDiscovery;
use InvalidArgumentException;
use N1ebieski\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemAction;
use N1ebieski\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemHandler;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\ClientResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\XadesSignature;
use N1ebieski\KSEFClient\Exceptions\ExceptionHandler;
use N1ebieski\KSEFClient\Exceptions\StatusException;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\Factories\ClientFactory;
use N1ebieski\KSEFClient\Factories\EncryptedKeyFactory;
use N1ebieski\KSEFClient\Factories\EncryptedTokenFactory;
use N1ebieski\KSEFClient\Factories\LoggerFactory;
use N1ebieski\KSEFClient\HttpClient\HttpClient;
use N1ebieski\KSEFClient\Requests\Auth\KsefToken\KsefTokenRequest;
use N1ebieski\KSEFClient\Requests\Auth\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureRequest;
use N1ebieski\KSEFClient\Resources\ClientResource;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\ApiUrl;
use N1ebieski\KSEFClient\ValueObjects\Certificate;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\BaseUri;
use N1ebieski\KSEFClient\ValueObjects\InternalId;
use N1ebieski\KSEFClient\ValueObjects\KsefPublicKey;
use N1ebieski\KSEFClient\ValueObjects\KsefToken;
use N1ebieski\KSEFClient\ValueObjects\LogPath;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\NipVatUe;
use N1ebieski\KSEFClient\ValueObjects\PeppolId;
use N1ebieski\KSEFClient\ValueObjects\RefreshToken;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\Challenge;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\SubjectIdentifierType;
use N1ebieski\KSEFClient\ValueObjects\Requests\ReferenceNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyCertificateUsage;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyId;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use Psr\Http\Client\ClientInterface as BaseClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;
use SensitiveParameter;
use Throwable;

final class ClientBuilder
{
    private ClientInterface $httpClient;

    private ?LoggerInterface $logger = null;

    private ExceptionHandlerInterface $exceptionHandler;

    private ?CacheInterface $cache = null;

    private ?Cache $valinorCache = null;

    private Mode $mode = Mode::Production;

    private ApiUrl $apiUrl;

    private ApiUrl $latarniaApiUrl;

    private ?KsefToken $ksefToken = null;

    private ?AccessToken $accessToken = null;

    private ?RefreshToken $refreshToken = null;

    private ?Certificate $certificate = null;

    private NIP | NipVatUe | InternalId | PeppolId $identifier;

    private ?EncryptionKey $encryptionKey = null;

    private Optional | bool $verifyCertificateChain;

    private int $asyncMaxConcurrency = 8;

    private bool $validateXml = true;

    private int $cacheTTL = 43200;

    private int $retryBackoff = 10;

    private int $retryUntil = 120;

    public function __construct()
    {
        $this->httpClient = ClientFactory::make(Psr18ClientDiscovery::find());
        $this->logger = LoggerFactory::make();
        $this->apiUrl = $this->mode->getApiUrl();
        $this->latarniaApiUrl = $this->mode->getLatarniaApiUrl();
        $this->verifyCertificateChain = new Optional();
    }

    public function withMode(Mode | string $mode): self
    {
        if ($mode instanceof Mode === false) {
            $mode = Mode::from($mode);
        }

        $this->mode = $mode;

        $this->apiUrl = $this->mode->getApiUrl();
        $this->latarniaApiUrl = $this->mode->getLatarniaApiUrl();

        if ($this->mode->isEquals(Mode::Test)) {
            $this->identifier = new NIP('1111111111');
        }

        return $this;
    }

    public function withEncryptionKey(#[SensitiveParameter] EncryptionKey | string $encryptionKey, #[SensitiveParameter] ?string $iv = null): self
    {
        if (is_string($encryptionKey)) {
            if ($iv === null) {
                throw new InvalidArgumentException('IV is required when key is string.');
            }

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

    public function withLatarniaApiUrl(ApiUrl | string $latarniaApiUrl): self
    {
        if ($latarniaApiUrl instanceof ApiUrl === false) {
            $latarniaApiUrl = ApiUrl::from($latarniaApiUrl);
        }

        $this->latarniaApiUrl = $latarniaApiUrl;

        return $this;
    }

    public function withKsefToken(#[SensitiveParameter] KsefToken | string $ksefToken): self
    {
        if ($ksefToken instanceof KsefToken === false) {
            $ksefToken = KsefToken::from($ksefToken);
        }

        $this->certificate = null;

        $this->ksefToken = $ksefToken;

        return $this;
    }

    public function withAccessToken(#[SensitiveParameter] AccessToken | string $accessToken, DateTimeInterface | string | null $validUntil = null): self
    {
        if ($accessToken instanceof AccessToken === false) {
            if (is_string($validUntil)) {
                $validUntil = new DateTimeImmutable($validUntil);
            }

            $accessToken = AccessToken::from($accessToken, $validUntil);
        }

        $this->accessToken = $accessToken;

        return $this;
    }

    public function withRefreshToken(#[SensitiveParameter] RefreshToken | string $refreshToken, DateTimeInterface | string | null $validUntil = null): self
    {
        if ($refreshToken instanceof RefreshToken === false) {
            if (is_string($validUntil)) {
                $validUntil = new DateTimeImmutable($validUntil);
            }

            $refreshToken = RefreshToken::from($refreshToken, $validUntil);
        }

        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function withCertificatePath(CertificatePath | string $certificatePath, #[SensitiveParameter] ?string $passphrase = null): self
    {
        if ($certificatePath instanceof CertificatePath === false) {
            $certificatePath = CertificatePath::from($certificatePath, $passphrase);
        }

        $certificate = CertificateFactory::makeFromCertificatePath($certificatePath);

        return $this->withCertificate($certificate);
    }

    public function withCertificate(Certificate | string $certificate, #[SensitiveParameter] ?string $privateKey = null, #[SensitiveParameter] ?string $passphrase = null): self
    {
        if ($certificate instanceof Certificate === false) {
            if ($privateKey === null) {
                throw new InvalidArgumentException('Private key is required when certificate is string.');
            }

            $certificate = CertificateFactory::makeFromPkcs8($certificate, $privateKey, $passphrase);
        }

        $this->ksefToken = null;

        $this->certificate = $certificate;

        return $this;
    }

    public function withHttpClient(BaseClientInterface $client): self
    {
        $this->httpClient = ClientFactory::make($client);

        return $this;
    }

    public function withLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function withIdentifier(NIP | NipVatUe | InternalId | PeppolId | string $identifier): self
    {
        if (is_string($identifier)) {
            $identifier = NIP::from($identifier);
        }

        $this->identifier = $identifier;

        return $this;
    }

    public function withVerifyCertificateChain(bool $verifyCertificateChain): self
    {
        $this->verifyCertificateChain = $verifyCertificateChain;

        return $this;
    }

    public function withAsyncMaxConcurrency(int $asyncMaxConcurrency): self
    {
        $this->asyncMaxConcurrency = $asyncMaxConcurrency;

        return $this;
    }

    public function withValidateXml(bool $validateXml): self
    {
        $this->validateXml = $validateXml;

        return $this;
    }

    /**
     * @param null|LogLevel::* $level
     */
    public function withLogPath(LogPath | string | null $logPath, ?string $level = LogLevel::DEBUG): self
    {
        if (is_string($logPath)) {
            $logPath = LogPath::from($logPath);
        }

        $this->logger = null;

        if ($level !== null) {
            $this->logger = LoggerFactory::make($logPath, $level);
        }

        return $this;
    }

    public function withExceptionHandler(ExceptionHandlerInterface $exceptionHandler): self
    {
        $this->exceptionHandler = $exceptionHandler;

        return $this;
    }

    /**
     * Sets the cache implementation and the default time-to-live (TTL) for cache entries.
     *
     * @param CacheInterface $cache The cache implementation to use.
     * @param int $cacheTTL The default time-to-live in seconds for cache entries.
     */
    public function withCache(CacheInterface $cache, int $cacheTTL = 43200): self
    {
        $this->cache = $cache;
        $this->cacheTTL = $cacheTTL;

        return $this;
    }

    public function withValinorCache(Cache $cache): self
    {
        $this->valinorCache = $cache;

        return $this;
    }

    /**
     * Sets retry timing for checking authorisation status during auto authorisation.
     *
     * @param int $retryBackoff Delay in seconds between retries.
     * @param int $retryUntil Maximum total retry time in seconds.
     */
    public function withRetryTiming(int $retryBackoff = 10, int $retryUntil = 120): self
    {
        $this->retryBackoff = $retryBackoff;
        $this->retryUntil = $retryUntil;

        return $this;
    }

    public function build(): ClientResource
    {
        $config = new Config(
            mode: $this->mode,
            baseUri: new BaseUri($this->apiUrl->value),
            latarniaBaseUri: new BaseUri($this->latarniaApiUrl->value),
            asyncMaxConcurrency: $this->asyncMaxConcurrency,
            validateXml: $this->validateXml,
            cacheTTL: $this->cacheTTL,
            accessToken: $this->accessToken,
            refreshToken: $this->refreshToken,
            encryptionKey: $this->encryptionKey,
        );

        $httpClient = new HttpClient(
            client: $this->httpClient,
            config: $config,
            logger: $this->logger
        );

        $this->exceptionHandler ??= new ExceptionHandler($this->logger);

        $client = new ClientResource(
            client: $httpClient,
            config: $config,
            exceptionHandler: $this->exceptionHandler,
            logger: $this->logger,
            cache: $this->cache,
            valinorCache: $this->valinorCache
        );

        if ($this->encryptionKey instanceof EncryptionKey) {
            $client = $client->withEncryptedKey($this->handleEncryptedKey($client));
        }

        if ($this->isAuthorisation()) {
            try {
                $authorisationAccessResponse = match (true) {
                    $this->certificate instanceof Certificate => $this->handleAuthorisationByCertificate($client),
                    $this->ksefToken instanceof KsefToken => $this->handleAuthorisationByKsefToken($client),
                };
            } catch (Throwable $throwable) {
                throw $this->exceptionHandler->handle($throwable);
            }

            /** @var object{referenceNumber: string, authenticationToken: object{token: string, validUntil: string}} $authorisationAccessResponse */
            $authorisationAccessResponse = $authorisationAccessResponse->object();

            $client = $client->withAccessToken(AccessToken::from(
                $authorisationAccessResponse->authenticationToken->token,
                new DateTimeImmutable($authorisationAccessResponse->authenticationToken->validUntil)
            ));

            Utility::retry(function () use ($client, $authorisationAccessResponse) {
                /** @var object{status: object{code: int, description: string, details?: array<int, string>}} $authorisationStatusResponse */
                $authorisationStatusResponse = $client->auth()->status(
                    new StatusRequest(ReferenceNumber::from($authorisationAccessResponse->referenceNumber))
                )->object();

                if ($authorisationStatusResponse->status->code === 200) {
                    return $authorisationStatusResponse;
                }

                if ($authorisationStatusResponse->status->code >= 400) {
                    throw $this->exceptionHandler->handle(new StatusException(
                        message: $authorisationStatusResponse->status->description,
                        code: $authorisationStatusResponse->status->code,
                        context: $authorisationStatusResponse
                    ));
                }
            }, $this->retryBackoff, $this->retryUntil);

            /** @var object{refreshToken: object{token: string, validUntil: string}, accessToken: object{token: string, validUntil: string}} $authorisationTokenResponse */
            $authorisationTokenResponse = $client->auth()->token()->redeem()->object();

            $client = $client
                ->withAccessToken(AccessToken::from(
                    token: $authorisationTokenResponse->accessToken->token,
                    validUntil: new DateTimeImmutable($authorisationTokenResponse->accessToken->validUntil)
                ))
                ->withRefreshToken(RefreshToken::from(
                    token: $authorisationTokenResponse->refreshToken->token,
                    validUntil: new DateTimeImmutable($authorisationTokenResponse->refreshToken->validUntil)
                ));
        }

        return $client;
    }

    private function isAuthorisation(): bool
    {
        return ! $this->accessToken instanceof AccessToken && (
            $this->ksefToken instanceof KsefToken || $this->certificate instanceof Certificate
        );
    }

    private function handleEncryptedKey(ClientResourceInterface $client): EncryptedKey
    {
        if ($this->encryptionKey instanceof EncryptionKey === false) {
            throw new RuntimeException('Encryption key is not set');
        }

        $securityResponse = $client->security()->publicKeyCertificates();

        $firstSymmetricKeyEncryptionCertificate = $securityResponse
            ->getFirstCertificateByUsage(PublicKeyCertificateUsage::SymmetricKeyEncryption);

        if ($firstSymmetricKeyEncryptionCertificate === null) {
            throw new RuntimeException('Symmetric key encryption certificate is not found');
        }

        $symmetricKeyEncryptionCertificate = base64_decode($firstSymmetricKeyEncryptionCertificate->certificate);

        $certificate = (new ConvertDerToPemHandler())->handle(new ConvertDerToPemAction(
            der: $symmetricKeyEncryptionCertificate,
            name: 'CERTIFICATE'
        ));

        $publicKeyId = isset($firstSymmetricKeyEncryptionCertificate->publicKeyId)
            ? PublicKeyId::from($firstSymmetricKeyEncryptionCertificate->publicKeyId)
            : null;

        $ksefPublicKey = KsefPublicKey::from($certificate, $publicKeyId);

        return EncryptedKeyFactory::make($this->encryptionKey, $ksefPublicKey);
    }

    private function handleAuthorisationByCertificate(ClientResourceInterface $client): ResponseInterface
    {
        if ( ! $this->certificate instanceof Certificate) {
            throw new RuntimeException('Certificate is not set');
        }

        /** @var object{challenge: string, timestamp: string} $challengeResponse */
        $challengeResponse = $client->auth()->challenge()->object();

        return $client->auth()->xadesSignature(
            new XadesSignatureRequest(
                certificate: $this->certificate,
                xadesSignature: new XadesSignature(
                    challenge: Challenge::from($challengeResponse->challenge),
                    contextIdentifierGroup: ContextIdentifierGroup::fromIdentifier($this->identifier),
                    subjectIdentifierType: SubjectIdentifierType::CertificateSubject
                ),
                verifyCertificateChain: $this->verifyCertificateChain
            )
        );
    }

    private function handleAuthorisationByKsefToken(ClientResourceInterface $client): ResponseInterface
    {
        if ( ! $this->ksefToken instanceof KsefToken) {
            throw new RuntimeException('KSEF token is not set');
        }

        /** @var object{challenge: string, timestamp: string, timestampMs: int} $challengeResponse */
        $challengeResponse = $client->auth()->challenge()->object();

        $securityResponse = $client->security()->publicKeyCertificates();

        $firstKsefTokenEncryptionCertificate = $securityResponse
            ->getFirstCertificateByUsage(PublicKeyCertificateUsage::KsefTokenEncryption);

        if ($firstKsefTokenEncryptionCertificate === null) {
            throw new RuntimeException('KSEF token encryption certificate is not found');
        }

        $ksefTokenEncryptionCertificate = base64_decode($firstKsefTokenEncryptionCertificate->certificate);

        $certificate = (new ConvertDerToPemHandler())->handle(new ConvertDerToPemAction(
            der: $ksefTokenEncryptionCertificate,
            name: 'CERTIFICATE'
        ));

        $publicKeyId = isset($firstKsefTokenEncryptionCertificate->publicKeyId)
            ? PublicKeyId::from($firstKsefTokenEncryptionCertificate->publicKeyId)
            : null;

        $ksefPublicKey = KsefPublicKey::from($certificate, $publicKeyId);

        $encryptedToken = EncryptedTokenFactory::make(
            ksefToken: $this->ksefToken,
            timestamp: $challengeResponse->timestampMs,
            ksefPublicKey: $ksefPublicKey
        );

        return $client->auth()->ksefToken(
            new KsefTokenRequest(
                challenge: Challenge::from($challengeResponse->challenge),
                contextIdentifierGroup: ContextIdentifierGroup::fromIdentifier($this->identifier),
                encryptedToken: $encryptedToken,
            )
        );
    }
}
