<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Online\Open;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\DTOs\HttpClient\Request;
use N1ebieski\KSEFClient\Requests\AbstractHandler;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\Method;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\Uri;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use RuntimeException;

final class OpenHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config
    ) {
    }

    public function handle(OpenRequest $request): ResponseInterface
    {
        if ($this->config->encryptedKey instanceof EncryptedKey === false) {
            throw new RuntimeException('Encrypted key is required to open session.');
        }

        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('sessions/online'),
            body: [
                ...$request->toBody(),
                'encryption' => $this->config->encryptedKey->toBody()
            ]
        ));
    }
}
