<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Exception;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Psr\Log\LoggerInterface;

final readonly class ExceptionHandler implements ExceptionHandlerInterface
{
    public function __construct(
        private ?LoggerInterface $logger = null
    ) {
    }

    public function handle(AbstractException $exception): void
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->error($exception->getMessage(), $exception->toArray());
        }

        throw $exception;
    }
}
