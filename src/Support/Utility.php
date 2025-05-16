<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use Closure;

final class Utility
{
    public static function retry(Closure $closure, int $backoff = 10, int $retryUntil = 120): mixed
    {
        $seconds = 0;

        while (true) {
            sleep($backoff);

            $result = $closure();

            if ($result !== null) {
                return $result;
            }

            $seconds += $backoff;

            if ($seconds > $retryUntil) {
                throw new \RuntimeException("Operation did not return a result after retrying for {$retryUntil} seconds.");
            }
        }
    }
}
