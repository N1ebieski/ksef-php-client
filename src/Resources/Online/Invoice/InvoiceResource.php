<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Actions\EncryptDocument\EncryptDocumentHandler;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice\InvoiceResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Get\GetHandler;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Get\GetRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendHandler;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Psr\Log\LoggerInterface;

final readonly class InvoiceResource extends AbstractResource implements InvoiceResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function send(SendRequest | array $request): ResponseInterface
    {
        if ($request instanceof SendRequest == false) {
            $request = SendRequest::from($request);
        }

        return new SendHandler(
            client: $this->client,
            encryptDocument: new EncryptDocumentHandler($this->logger),
            config: $this->config
        )->handle($request);
    }

    public function status(StatusRequest | array $request): ResponseInterface
    {
        if ($request instanceof StatusRequest == false) {
            $request = StatusRequest::from($request);
        }

        return new StatusHandler($this->client)->handle($request);
    }

    public function get(GetRequest | array $request): ResponseInterface
    {
        if ($request instanceof GetRequest == false) {
            $request = GetRequest::from($request);
        }

        return new GetHandler($this->client)->handle($request);
    }
}
