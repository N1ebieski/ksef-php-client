<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Testdata\Certificates;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Certificates\CertificatesResourceInterface;
use N1ebieski\KSEFClient\Requests\Testdata\Certificates\Update\UpdateHandler;
use N1ebieski\KSEFClient\Requests\Testdata\Certificates\Update\UpdateRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class CertificatesResource extends AbstractResource implements CertificatesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function update(UpdateRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof UpdateRequest === false) {
                $request = UpdateRequest::from($request, $this->valinorCache);
            }

            return (new UpdateHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
