<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Sessions\Batch;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Actions\EncryptDocument\EncryptDocumentHandler;
use N1ebieski\KSEFClient\Actions\SplitDocumentIntoParts\SplitDocumentIntoPartsHandler;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Sessions\Batch\BatchResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\Close\CloseHandler;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\Close\CloseRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendHandler;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendResponse;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendXmlRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendZipRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Psr\Log\LoggerInterface;
use Throwable;

final class BatchResource extends AbstractResource implements BatchResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function openAndSend(OpenAndSendRequest | OpenAndSendXmlRequest | OpenAndSendZipRequest | array $request): OpenAndSendResponse
    {
        try {
            if (is_array($request)) {
                $request = OpenAndSendRequest::from($request, $this->valinorCache);
            }

            return (new OpenAndSendHandler(
                client: $this->client,
                encryptDocument: new EncryptDocumentHandler($this->logger),
                splitDocumentIntoParts: new SplitDocumentIntoPartsHandler(),
                config: $this->config
            ))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function close(CloseRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof CloseRequest === false) {
                $request = CloseRequest::from($request, $this->valinorCache);
            }

            return (new CloseHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
