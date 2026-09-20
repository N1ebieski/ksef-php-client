<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Auth;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Actions\SignDocument\SignDocumentHandler;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Auth\AuthResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Auth\Challenge\ChallengeHandler;
use N1ebieski\KSEFClient\Requests\Auth\KsefToken\KsefTokenHandler;
use N1ebieski\KSEFClient\Requests\Auth\KsefToken\KsefTokenRequest;
use N1ebieski\KSEFClient\Requests\Auth\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Auth\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureHandler;
use N1ebieski\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureRequest;
use N1ebieski\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureXmlRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Auth\Sessions\SessionsResource;
use N1ebieski\KSEFClient\Resources\Auth\Token\TokenResource;
use Throwable;

final class AuthResource extends AbstractResource implements AuthResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function challenge(): ResponseInterface
    {
        try {
            return new ChallengeHandler($this->client)->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function xadesSignature(XadesSignatureRequest | XadesSignatureXmlRequest | array $request): ResponseInterface
    {
        try {
            if (is_array($request)) {
                $request = XadesSignatureRequest::from($request, $this->valinorCache);
            }

            return new XadesSignatureHandler(
                client: $this->client,
                signDocument: new SignDocumentHandler(new ConvertEcdsaDerToRawHandler()),
                config: $this->config
            )->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function ksefToken(KsefTokenRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof KsefTokenRequest === false) {
                $request = KsefTokenRequest::from($request, $this->valinorCache);
            }

            return new KsefTokenHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function status(StatusRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof StatusRequest === false) {
                $request = StatusRequest::from($request, $this->valinorCache);
            }

            return new StatusHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function token(): TokenResource
    {
        try {
            return new TokenResource($this->client, $this->config, $this->exceptionHandler);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function sessions(): SessionsResource
    {
        try {
            return new SessionsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
