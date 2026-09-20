<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Invoices\Query;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Invoices\Query\QueryResourceInterface;
use N1ebieski\KSEFClient\Requests\Invoices\Query\Metadata\MetadataHandler;
use N1ebieski\KSEFClient\Requests\Invoices\Query\Metadata\MetadataRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class QueryResource extends AbstractResource implements QueryResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function metadata(MetadataRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof MetadataRequest === false) {
                $request = MetadataRequest::from($request, $this->valinorCache);
            }

            return new MetadataHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
