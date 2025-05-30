<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use N1ebieski\KSEFClient\ValueObjects\LogPath;
use Psr\Log\LoggerInterface;
use PsrDiscovery\Discover;
use PsrDiscovery\Entities\CandidateEntity;

final readonly class LoggerFactory extends AbstractFactory
{
    /**
     * @param null|string $level
     * @see \Psr\Log\LogLevel
     */
    public static function make(?LogPath $logPath = null, string | int | null $level = null): ?LoggerInterface
    {
        $logger = Discover::log();

        if ($logger !== null) {
            return $logger;
        }

        if ( ! $logPath instanceof LogPath) {
            return null;
        }

        $candidates = array_map(fn (CandidateEntity $candidate): string => $candidate->getPackage(), Discover::logs());

        if (in_array('monolog/monolog', $candidates)) {
            $handler = new StreamHandler(
                $logPath->value,
                $level !== null ? Level::fromName($level) : Level::Debug
            );
            $formatter = new LineFormatter(allowInlineLineBreaks: true);
            $handler->setFormatter($formatter);

            return new Logger('ksef-php-client', [$handler]);
        }

        return null;
    }
}
