<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Testdata\Context;

use CuyZ\Valinor\Cache\Cache;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\Context\ContextResourceInterface;
use N1ebieski\KSEFClient\Requests\Testdata\Context\Block\BlockHandler;
use N1ebieski\KSEFClient\Requests\Testdata\Context\Block\BlockRequest;
use N1ebieski\KSEFClient\Requests\Testdata\Context\Unblock\UnblockHandler;
use N1ebieski\KSEFClient\Requests\Testdata\Context\Unblock\UnblockRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use Throwable;

final class ContextResource extends AbstractResource implements ContextResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function block(BlockRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof BlockRequest === false) {
                $request = BlockRequest::from($request, $this->valinorCache);
            }

            return new BlockHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function unblock(UnblockRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof UnblockRequest === false) {
                $request = UnblockRequest::from($request, $this->valinorCache);
            }

            return new UnblockHandler($this->client)->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
