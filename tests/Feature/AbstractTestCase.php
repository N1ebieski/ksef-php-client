<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Feature;

use GuzzleHttp\Client;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Contracts\Resources\ClientResourceInterface;
use N1ebieski\KSEFClient\Support\Env;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\InternalId;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    public Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();
    }

    public function createClient(
        NIP|InternalId|string|null $identifier = null,
        ?string $certificatePath = null,
        ?string $certificatePassphrase = null,
        ?EncryptionKey $encryptionKey = null
    ): ClientResourceInterface {
        $client = (new ClientBuilder())
            ->withMode(Mode::Test)
            ->withIdentifier($identifier ?? Env::string('NIP_1'))
            ->withLogPath(Utility::basePath('var/logs/monolog.log'))
            ->withCertificatePath(
                Utility::basePath($certificatePath ?? Env::string('CERTIFICATE_PATH_1')),
                $certificatePassphrase ?? Env::string('CERTIFICATE_PASSPHRASE_1')
            );

        if ($encryptionKey instanceof EncryptionKey) {
            $client = $client->withEncryptionKey($encryptionKey);
        }

        return $client->build();
    }

    public function revokeKsefToken(string $referenceNumber): void
    {
        $client = $this->createClient();

        $response = $client->tokens()->revoke([
            'referenceNumber' => $referenceNumber
        ])->status();

        expect($response)->toBe(204);
    }

    public function revokeCurrentSession(ClientResourceInterface $client): void
    {
        $response = $client->auth()->sessions()->revokeCurrent()->status();

        expect($response)->toBe(204);
    }
}
