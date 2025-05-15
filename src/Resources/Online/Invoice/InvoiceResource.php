<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice;

use N1ebieski\KSEFClient\Actions\LogXml\LogXmlHandler;
use N1ebieski\KSEFClient\Contracts\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Online\Invoice\InvoiceResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendHandler;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendResponse;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusResponse;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;

final readonly class InvoiceResource extends AbstractResource implements InvoiceResourceInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private Config $config
    ) {
    }

    public function send(SendRequest | array $dto): SendResponse
    {
        if ($dto instanceof SendRequest == false) {
            $dto = SendRequest::from($dto);
        }

        return new SendHandler(
            client: $this->client,
            logXml: new LogXmlHandler($this->config),
            config: $this->config
        )->handle($dto);
    }

    public function status(StatusRequest | array $dto): StatusResponse
    {
        if ($dto instanceof StatusRequest == false) {
            $dto = StatusRequest::from($dto);
        }

        return new StatusHandler($this->client)->handle($dto);
    }
}
